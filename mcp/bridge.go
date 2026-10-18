package main

import (
	"context"
	"encoding/json"
	"errors"
	"fmt"
	"log"
	"net/http"
	"slices"
	"strings"
	"sync"
	"time"

	"github.com/coder/websocket"
)

// Bridge forwards api requests to the mcp/bridge page open in the user's browser.
// The website is hosted on infinityfree which only lets real browsers through,
// so the page does the actual fetch() and sends the response back over the websocket.
type Bridge struct {
	allowedOrigins []string
	bridgePageURL  string

	mu        sync.Mutex
	conn      *websocket.Conn
	connected chan struct{} // closed when a page connects, replaced when it disconnects
	nextID    int
	pending   map[int]chan bridgeResponse
}

type bridgeRequest struct {
	ID     int    `json:"id"`
	Method string `json:"method"`
	Path   string `json:"path"`
	Body   any    `json:"body,omitempty"`
}

type bridgeResponse struct {
	ID     int    `json:"id"`
	Status int    `json:"status"`
	Body   string `json:"body"`
}

const (
	waitForPageTimeout = 15 * time.Second
	requestTimeout     = 60 * time.Second
)

func NewBridge(allowedOrigins []string, bridgePageURL string) *Bridge {
	return &Bridge{
		allowedOrigins: allowedOrigins,
		bridgePageURL:  bridgePageURL,
		connected:      make(chan struct{}),
		pending:        map[int]chan bridgeResponse{},
	}
}

func (b *Bridge) ServeHTTP(w http.ResponseWriter, r *http.Request) {
	if r.URL.Path != "/bridge" {
		fmt.Fprintf(w, "MCP bridge for the website. Open %s in your browser.\n", b.bridgePageURL)
		return
	}
	// only the website itself may connect, otherwise any page you visit could use the mcp key
	origin := strings.TrimRight(r.Header.Get("Origin"), "/")
	if !slices.Contains(b.allowedOrigins, origin) {
		log.Printf("rejected bridge connection from origin %q (allowed: %v)", origin, b.allowedOrigins)
		http.Error(w, "origin not allowed", http.StatusForbidden)
		return
	}
	conn, err := websocket.Accept(w, r, &websocket.AcceptOptions{InsecureSkipVerify: true}) // origin checked above
	if err != nil {
		log.Printf("websocket accept failed: %v", err)
		return
	}
	conn.SetReadLimit(16 << 20)
	b.setConn(conn)
	log.Printf("bridge page connected from %s", origin)
	b.readLoop(r.Context(), conn)
	b.clearConn(conn)
	log.Printf("bridge page disconnected")
}

func (b *Bridge) setConn(conn *websocket.Conn) {
	b.mu.Lock()
	defer b.mu.Unlock()
	if b.conn != nil {
		// newest tab wins
		b.conn.Close(websocket.StatusNormalClosure, "another bridge page connected")
	} else {
		close(b.connected)
	}
	b.conn = conn
}

func (b *Bridge) clearConn(conn *websocket.Conn) {
	b.mu.Lock()
	defer b.mu.Unlock()
	if b.conn != conn {
		return
	}
	b.conn = nil
	b.connected = make(chan struct{})
	for id, ch := range b.pending {
		ch <- bridgeResponse{ID: id, Status: 0, Body: `{"success":false,"message":"bridge page disconnected before answering"}`}
		delete(b.pending, id)
	}
}

func (b *Bridge) readLoop(ctx context.Context, conn *websocket.Conn) {
	for {
		_, data, err := conn.Read(ctx)
		if err != nil {
			return
		}
		var resp bridgeResponse
		if err := json.Unmarshal(data, &resp); err != nil {
			log.Printf("invalid message from bridge page: %v", err)
			continue
		}
		b.mu.Lock()
		ch, ok := b.pending[resp.ID]
		delete(b.pending, resp.ID)
		b.mu.Unlock()
		if ok {
			ch <- resp
		}
	}
}

// Do sends a request trough the browser and returns the http status and response body.
func (b *Bridge) Do(ctx context.Context, method, path string, body any) (int, string, error) {
	conn, err := b.waitForConn(ctx)
	if err != nil {
		return 0, "", err
	}

	b.mu.Lock()
	b.nextID++
	req := bridgeRequest{ID: b.nextID, Method: method, Path: path, Body: body}
	ch := make(chan bridgeResponse, 1)
	b.pending[req.ID] = ch
	b.mu.Unlock()
	defer func() {
		b.mu.Lock()
		delete(b.pending, req.ID)
		b.mu.Unlock()
	}()

	data, err := json.Marshal(req)
	if err != nil {
		return 0, "", err
	}
	ctx, cancel := context.WithTimeout(ctx, requestTimeout)
	defer cancel()
	if err := conn.Write(ctx, websocket.MessageText, data); err != nil {
		return 0, "", fmt.Errorf("sending request to the bridge page failed: %w", err)
	}
	select {
	case resp := <-ch:
		return resp.Status, resp.Body, nil
	case <-ctx.Done():
		return 0, "", fmt.Errorf("the bridge page did not answer in time (%s). Ask the user to check the %s tab", requestTimeout, b.bridgePageURL)
	}
}

func (b *Bridge) waitForConn(ctx context.Context) (*websocket.Conn, error) {
	b.mu.Lock()
	conn, connected := b.conn, b.connected
	b.mu.Unlock()
	if conn != nil {
		return conn, nil
	}
	select {
	case <-connected:
		b.mu.Lock()
		defer b.mu.Unlock()
		if b.conn != nil {
			return b.conn, nil
		}
	case <-time.After(waitForPageTimeout):
	case <-ctx.Done():
		return nil, ctx.Err()
	}
	return nil, errors.New("no browser is connected to the MCP server. Ask the user to open " + b.bridgePageURL +
		" in their browser and keep the tab open, then try again")
}
