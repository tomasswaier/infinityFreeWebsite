# test-questions MCP server

Lets an AI assistant (Claude Code, Claude Desktop, ...) add questions to tests on the website.

## Why it works like this

The website runs on InfinityFree, which only runs PHP and blocks every request that doesn't
come from a real browser (it checks for a JS-generated `__test` cookie). So the MCP server
can't call the website directly. Instead:

```
AI assistant --stdio--> test-questions-mcp --websocket (127.0.0.1:8765)--> /mcp/bridge tab in your browser --fetch--> /mcp/*
```

The `/mcp/bridge` page does the actual requests. They come from a real browser, so the bot
check passes, and they carry your login session. So there is no API key: the `/mcp/*` endpoints
use the same permissions as the question creator. You have to be logged in as an **admin** (any
test) or a **supervisor** (only tests of the schools you supervise).

## Setup

1. **Build** (Go 1.26+, see go.mod):
   ```
   cd mcp && go build -o test-questions-mcp .
   ```
2. **Register it with your assistant**, e.g. Claude Code:
   ```
   claude mcp add test-questions -- /absolute/path/to/mcp/test-questions-mcp --localhost
   ```
   Claude Desktop and other clients: command `/absolute/path/to/mcp/test-questions-mcp`, `"args": ["--localhost"]`.
   Settings are optional; it reads them from the environment or a `.env` in its current directory
   (`MCP_SITE`, `MCP_BRIDGE_PORT`, `MCP_HTTP_ADDR`, `MCP_ALLOW_ORIGIN`).
3. **Log in** on the website as an admin or supervisor, then open
   **https://maryann.free.nf/mcp/bridge** and keep the tab open. It reconnects by itself, so the order
   doesn't matter. Your browser may ask to allow access to apps/devices on this computer; allow it.
   Use **https**: Firefox/Zen and Chrome block plain `http://` pages from reaching 127.0.0.1.
   The login expires after the session lifetime (`SESSION_LIFETIME`, 2 hours by default) of inactivity;
   log in with "remember me" or just reload the bridge page when a tool says the session expired.

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
  tunnel url can add questions as you (the user logged in on the bridge tab) while it's running,
  so keep it to yourself. Requests coming from web
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
