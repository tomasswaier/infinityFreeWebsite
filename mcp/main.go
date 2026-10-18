// MCP server that lets an AI assistant add questions to tests on the website.
// It talks to the website trough the mcp/bridge page open in the user's browser. See README.md.
package main

import (
	"context"
	"errors"
	"flag"
	"fmt"
	"io"
	"log"
	"net"
	"net/http"
	"os"
	"strings"

	"github.com/modelcontextprotocol/go-sdk/mcp"
)

const usageText = `test-questions-mcp - MCP server for adding questions to tests on the website.

Usage:
  test-questions-mcp --localhost [options]   for AI apps running on this computer
  test-questions-mcp --http [options]        for AI apps that connect over http (e.g. ChatGPT in the browser)

You have to pick exactly one of --localhost or --http.

  --localhost   The AI app starts this program itself and talks to it over stdin/stdout.
                Use this for Claude Code, Claude Desktop, Codex CLI, ...
                  claude mcp add test-questions -- /path/to/test-questions-mcp --localhost
  --http        Runs an MCP http server on --http-addr (endpoint /mcp). Apps on the internet
                can only reach it trough a tunnel, e.g.  ngrok http 8080
                and then use  https://<tunnel>/mcp  as the server url.
                There is no password, anyone with the tunnel url can add questions.

In both modes, keep <site>/mcp/bridge open in your browser. The requests to the website go
trough that tab, because the hosting blocks everything that isn't a real browser.

Settings are read from the environment and from .env in the current directory:
  MCP_API_KEY       (required) same value as MCP_API_KEY in the website's .env
  MCP_SITE, MCP_BRIDGE_PORT, MCP_HTTP_ADDR, MCP_ALLOW_ORIGIN   defaults for the options below

Options:
`

func main() {
	// stdout is used by the mcp protocol in --localhost mode so all logging goes to stderr
	log.SetOutput(os.Stderr)
	log.SetPrefix("[test-questions-mcp] ")
	log.SetFlags(log.LstdFlags)

	// has to happen before the flags because their defaults come from the environment
	dotEnvFound, dotEnvErr := loadDotEnv(".env")

	// errors are reported by usageError so a typo gives a short message instead of the whole help
	flag.CommandLine = flag.NewFlagSet(os.Args[0], flag.ContinueOnError)
	flag.CommandLine.SetOutput(io.Discard)
	localhostMode := flag.Bool("localhost", false, "talk to a local AI app over stdin/stdout")
	httpMode := flag.Bool("http", false, "run an MCP http server (for remote AI apps, use with a tunnel)")
	httpAddr := flag.String("http-addr", envOr("MCP_HTTP_ADDR", "127.0.0.1:8080"), "address the --http server listens on")
	site := flag.String("site", envOr("MCP_SITE", "http://maryann.free.nf"), "url of the website")
	port := flag.String("port", envOr("MCP_BRIDGE_PORT", "8765"), "local port the bridge page connects to")
	extraOrigins := flag.String("allow-origin", os.Getenv("MCP_ALLOW_ORIGIN"), "extra comma separated origins allowed to open the bridge, e.g. http://localhost:8000")
	if err := flag.CommandLine.Parse(os.Args[1:]); errors.Is(err, flag.ErrHelp) {
		fmt.Fprint(os.Stdout, usageText)
		flag.CommandLine.SetOutput(os.Stdout)
		flag.PrintDefaults()
		os.Exit(0)
	} else if err != nil {
		usageError("%v", err)
	}

	if flag.NArg() > 0 {
		usageError("unknown argument %q", flag.Arg(0))
	}
	if *localhostMode == *httpMode {
		usageError("pick exactly one of --localhost or --http")
	}
	if dotEnvErr != nil {
		log.Fatalf("reading .env failed: %v", dotEnvErr)
	}
	if dotEnvFound {
		log.Printf("loaded .env from the current directory")
	}

	apiKey := os.Getenv("MCP_API_KEY")
	if apiKey == "" {
		usageError("MCP_API_KEY is not set. Put it into .env in the current directory (or the environment). It has to be the same as MCP_API_KEY in the website's .env")
	}

	siteURL := strings.TrimRight(*site, "/")
	bridge := NewBridge(apiKey, allowedOrigins(siteURL, *extraOrigins), siteURL+"/mcp/bridge")
	listener, err := net.Listen("tcp", "127.0.0.1:"+*port)
	if err != nil {
		log.Fatalf("can't listen on 127.0.0.1:%s (is another instance running?): %v", *port, err)
	}
	go func() {
		if err := http.Serve(listener, bridge); err != nil {
			log.Fatalf("bridge server stopped: %v", err)
		}
	}()
	log.Printf("waiting for the bridge page on 127.0.0.1:%s. Open %s/mcp/bridge in your browser", *port, siteURL)

	server := mcp.NewServer(&mcp.Implementation{Name: "test-questions", Version: "1.0.0"}, &mcp.ServerOptions{
		Instructions: "Adds questions to tests on the study website. Call list_tests first to find the test_id. " +
			"The requests go trough a browser tab the user has open (" + siteURL + "/mcp/bridge); " +
			"if a tool says no browser is connected, ask the user to open that page.",
	})
	registerTools(server, bridge)
	registerTableTools(server, bridge)

	if *localhostMode {
		if err := server.Run(context.Background(), &mcp.StdioTransport{}); err != nil {
			log.Fatal(err)
		}
		return
	}

	handler := mcp.NewStreamableHTTPHandler(func(*http.Request) *mcp.Server { return server }, &mcp.StreamableHTTPOptions{
		// a tunnel forwards requests to 127.0.0.1 with its own Host header which this protection would reject.
		// rejectBrowsers covers what it protected against
		DisableLocalhostProtection: true,
	})
	mux := http.NewServeMux()
	mux.Handle("/mcp", rejectBrowsers(handler))
	log.Printf("MCP http server listening on http://%s/mcp", *httpAddr)
	if err := http.ListenAndServe(*httpAddr, mux); err != nil {
		log.Fatalf("http server stopped: %v", err)
	}
}

func usageError(format string, args ...any) {
	fmt.Fprintf(os.Stderr, "error: "+format+"\n\nRun with --help to see how to use it.\n", args...)
	os.Exit(2)
}

func allowedOrigins(siteURL, extra string) []string {
	origins := []string{siteURL}
	// infinityfree serves both http and https
	if after, ok := strings.CutPrefix(siteURL, "http://"); ok {
		origins = append(origins, "https://"+after)
	} else if after, ok := strings.CutPrefix(siteURL, "https://"); ok {
		origins = append(origins, "http://"+after)
	}
	for _, origin := range strings.Split(extra, ",") {
		if origin = strings.TrimRight(strings.TrimSpace(origin), "/"); origin != "" {
			origins = append(origins, origin)
		}
	}
	return origins
}

// rejectBrowsers blocks requests made by web pages (browsers always send Origin on POST).
// MCP clients like ChatGPT's backend don't, so this only stops random websites you visit
// from calling the server on 127.0.0.1
func rejectBrowsers(next http.Handler) http.Handler {
	return http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
		if origin := r.Header.Get("Origin"); origin != "" {
			log.Printf("rejected http request from a web page (origin %q)", origin)
			http.Error(w, "requests from web pages are not allowed", http.StatusForbidden)
			return
		}
		next.ServeHTTP(w, r)
	})
}

func envOr(name, fallback string) string {
	if value := os.Getenv(name); value != "" {
		return value
	}
	return fallback
}
