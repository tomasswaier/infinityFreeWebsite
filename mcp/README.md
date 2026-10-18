# test-questions MCP server

Lets an AI assistant (Claude Code, Claude Desktop, ...) add questions to tests on the website.

## Why it works like this

The website runs on InfinityFree, which only runs PHP and blocks every request that doesn't
come from a real browser (it checks for a JS-generated `__test` cookie). So the MCP server
can't call the website directly. Instead:

```
AI assistant --stdio--> test-questions-mcp --websocket (127.0.0.1:8765)--> /mcp/bridge tab in your browser --fetch--> /api/mcp/*
```

The `/mcp/bridge` page does the actual requests. They come from a real browser, so the bot
check passes. The `/api/mcp/*` endpoints require the shared key `MCP_API_KEY` (`X-MCP-Key` header).

## Setup

1. **Website**: set a key in the website's `.env` (on InfinityFree, edit it over FTP):
   ```
   MCP_API_KEY=<long random string>   # php -r "echo bin2hex(random_bytes(32));"
   ```
   If the key is empty, the MCP api is disabled. If you use `php artisan config:cache`, re-run it.
2. **Build** (Go 1.26+, see go.mod):
   ```
   cd mcp && go build -o test-questions-mcp .
   ```
3. **Give the server the key.** On startup it reads `.env` from its **current working directory**
   (values already set in the real environment win). Running it from the repo root picks up the
   website's own `.env`, which already contains `MCP_API_KEY`. Otherwise put a `.env` next to where
   you start it:
   ```
   MCP_API_KEY=<same key as the website>
   # optional: MCP_SITE=..., MCP_BRIDGE_PORT=..., MCP_ALLOW_ORIGIN=...
   ```
   Register it with your assistant, e.g. Claude Code (it starts MCP servers in the directory you run `claude` from):
   ```
   claude mcp add test-questions -- /absolute/path/to/mcp/test-questions-mcp --localhost
   ```
   Claude Desktop and other clients may start it in a different directory. There, either set `"cwd"`
   if the client supports it, or pass the key through `"env": {"MCP_API_KEY": "..."}` instead.
   Don't forget `"args": ["--localhost"]`.
4. Open **http://maryann.free.nf/mcp/bridge** in your browser and keep the tab open. It reconnects
   by itself, so the order doesn't matter. Chrome may ask to allow access to "apps on this device";
   click allow.

## Modes

You have to pass exactly one of these (`test-questions-mcp --help` explains everything):

- `--localhost`: the AI app on your computer starts the server and talks to it over stdin/stdout.
  Use this for Claude Code, Claude Desktop, Codex CLI, ...
- `--http`: runs an MCP http server at `http://127.0.0.1:8080/mcp`. Use this for apps that connect
  from the internet, like ChatGPT in the browser. They can only reach it through a tunnel:
  ```
  ./test-questions-mcp --http
  ngrok http 8080
  ```
  Then use `https://<your-tunnel>/mcp` as the server url. There's no password: anyone with the
  tunnel url can add questions while it's running, so keep it to yourself. Requests coming from web
  pages (with an `Origin` header) are rejected, so websites you visit can't call it.

The `/mcp/bridge` tab has to be open in both modes.

## Options (flag / env)

| flag | env | default |
|---|---|---|
| `--site` | `MCP_SITE` | `http://maryann.free.nf` |
| `--port` | `MCP_BRIDGE_PORT` | `8765` (change it on the bridge page too) |
| `--http-addr` | `MCP_HTTP_ADDR` | `127.0.0.1:8080` (only with `--http`) |
| `--allow-origin` | `MCP_ALLOW_ORIGIN` | extra origins allowed to connect, comma separated |

Local development: `--site http://localhost:8000`, then open http://localhost:8000/mcp/bridge.

## Tools

- `list_tests`, `list_questions`
- `add_write_in_question`: fill-in-the-blank (`write_in`)
- `add_true_false_question`: mark statements true/false (`boolean_choice`)
- `add_single_choice_question`: exactly one correct option (`boolean_choice_one_correct`)
- `add_dropdown_question`: dropdowns (`one_from_many`)
- `add_open_answer_question`: free text (`open_answer`)
- `add_choice_table_question`: grid, one radio button per column in every row (`multiple_choice`)
- `add_fill_in_table_question`: table where some cells are typed by the student (`fill_in_table`)

Write-in answers and fill-in-table answer cells accept several correct answers separated by `;` (no spaces), e.g. `a;A`.

Images are not supported yet.
