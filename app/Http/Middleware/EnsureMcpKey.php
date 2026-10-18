<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureMcpKey
{
    /**
     * Lets the request trough only if it has the shared MCP_API_KEY in the X-MCP-Key header.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = config('services.mcp.key');
        if (!$key) {
            return response()->json(['success' => false, 'message' => 'MCP api is disabled. MCP_API_KEY is not set on the server'], 503);
        }
        if (!hash_equals($key, (string) $request->header('X-MCP-Key'))) {
            return response()->json(['success' => false, 'message' => 'Invalid MCP key'], 401);
        }
        return $next($request);
    }
}
