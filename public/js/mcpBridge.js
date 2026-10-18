// Forwards requests from the local MCP server (mcp/ folder) to the website.
// Message from the MCP server: {id, method, path, key, body}  (key = MCP_API_KEY, sent as X-MCP-Key)
// Message back:               {id, status, body}  (body is the raw response text)
// Requests are made from this page so they pass the infinityfree bot check.

const RETRY_MS = 3000;
const MAX_LOG_LINES = 200;

const portInput = document.getElementById('mcp_port');
const statusLabel = document.getElementById('mcp_status');
const logList = document.getElementById('mcp_log');

let socket = null;
let retryTimer = null;

try {
  const savedPort = localStorage.getItem('mcp_port');
  if (savedPort) {
    portInput.value = savedPort;
  }
} catch (e) {}

function setStatus(text, connected) {
  statusLabel.innerText = text;
  statusLabel.className = 'rounded-full px-3 py-1 text-sm ' +
      (connected ? 'bg-green-100 text-green-800'
                 : 'bg-project-light-blue text-project-dark-blue');
}

function log(text, isError = false) {
  const line = document.createElement('li');
  line.innerText = new Date().toLocaleTimeString() + '  ' + text;
  if (isError) {
    line.className = 'text-red-700';
  }
  logList.prepend(line);
  while (logList.children.length > MAX_LOG_LINES) {
    logList.lastChild.remove();
  }
}

async function forward(message) {
  // the bridge only ever touches the mcp endpoints, never the rest of the site
  if (typeof message.path !== 'string' || !message.path.startsWith('/api/mcp/')) {
    return {status : 400, body : JSON.stringify({success : false, message : 'bridge only forwards /api/mcp/ paths'})};
  }
  const options = {
    method : message.method || 'GET',
    credentials : 'same-origin',
    headers : {
      'Accept' : 'application/json',
      'X-MCP-Key' : message.key || '',
    },
  };
  if (message.body !== undefined && message.body !== null) {
    options.headers['Content-Type'] = 'application/json';
    options.body = JSON.stringify(message.body);
  }
  const response = await fetch(MCP_BASE_URL + message.path, options);
  let body = await response.text();
  if ((response.headers.get('content-type') || '').includes('text/html')) {
    // most likely the hosting's bot check page or a laravel error page
    body = JSON.stringify({success : false, message : 'Website answered with an html page instead of json (status ' + response.status + '). Ask the user to reload the MCP bridge page.'});
  }
  return {status : response.status, body : body};
}

async function handleMessage(event) {
  let message;
  try {
    message = JSON.parse(event.data);
  } catch (e) {
    log('Received invalid message from MCP server', true);
    return;
  }
  log('-> ' + (message.method || 'GET') + ' ' + message.path);
  let result;
  try {
    result = await forward(message);
  } catch (e) {
    result = {status : 0, body : JSON.stringify({success : false, message : 'Request from the browser failed: ' + e.message})};
  }
  log('<- ' + result.status + ' ' + message.path, result.status < 200 || result.status >= 300);
  if (socket && socket.readyState === WebSocket.OPEN) {
    socket.send(JSON.stringify({id : message.id, status : result.status, body : result.body}));
  }
}

function scheduleRetry() {
  clearTimeout(retryTimer);
  retryTimer = setTimeout(connect, RETRY_MS);
}

function connect() {
  clearTimeout(retryTimer);
  if (socket) {
    socket.onclose = null;
    socket.close();
  }
  const port = parseInt(portInput.value, 10) || 8765;
  try {
    localStorage.setItem('mcp_port', port);
  } catch (e) {}
  setStatus('Connecting to 127.0.0.1:' + port + ' ...', false);
  try {
    socket = new WebSocket('ws://127.0.0.1:' + port + '/bridge');
  } catch (e) {
    setStatus('Could not connect: ' + e.message, false);
    scheduleRetry();
    return;
  }
  socket.onopen = function() {
    setStatus('Connected to MCP server on port ' + port, true);
    log('Connected');
  };
  socket.onmessage = handleMessage;
  socket.onclose = function() {
    setStatus('Waiting for MCP server on port ' + port + ' (retrying) ...', false);
    scheduleRetry();
  };
}

document.getElementById('mcp_connect').onclick = connect;
connect();
