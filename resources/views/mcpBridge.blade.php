@extends('layouts.app')
@section('mainContent')
<div class="mx-auto w-full max-w-4xl px-4 py-8 sm:px-6 text-project-super-blue">
    <header class="rounded-2xl bg-project-light-blue px-6 py-7 sm:px-10">
        <h1 class="text-3xl font-bold sm:text-4xl">MCP bridge</h1>
        <p class="mt-1 max-w-prose text-project-dark-blue/80">
            Keep this tab open while your AI assistant adds questions. The MCP server running on your computer
            sends its requests here and this page forwards them to the website as you
            (<span class="font-semibold">{{ Auth::user()->name }}</span>), so it can only add questions to tests you are allowed to edit.
            The hosting blocks requests that don't come from a browser, which is why it works like this.
        </p>
    </header>

    <section class="mt-6 rounded-2xl border border-project-blue/60 bg-white px-6 py-5 shadow-sm">
        <div class="flex flex-wrap items-center gap-4">
            <label for="mcp_port" class="font-semibold">Local MCP port</label>
            <input id="mcp_port" type="number" min="1" max="65535" value="8765"
                   class="w-28 rounded-lg border border-project-blue bg-project-white px-3 py-1">
            <button id="mcp_connect" type="button"
                    class="rounded-lg bg-project-blue px-4 py-2 font-semibold text-project-super-blue transition-colors hover:bg-project-light-blue">
                Reconnect
            </button>
            <span id="mcp_status" class="rounded-full bg-project-light-blue px-3 py-1 text-sm text-project-dark-blue">Not connected</span>
        </div>
        <p class="mt-3 text-sm text-project-dark-blue/80">
            The page keeps retrying in the background, so it doesn't matter whether you open it before or after starting the assistant.
            Your browser may ask for permission to access apps on this device. Allow it, otherwise the page can't reach the MCP server.
        </p>
    </section>

    <section class="mt-6">
        <h2 class="mb-2 border-b border-project-light-blue pb-2 text-xl font-bold text-project-dark-blue">Activity</h2>
        <ul id="mcp_log" class="space-y-1 font-mono text-sm"></ul>
    </section>
</div>
<script>
    const MCP_BASE_URL = @json(rtrim(url('/'), '/'));
</script>
<script src="{{ asset('js/mcpBridge.js') }}"></script>
@endsection
