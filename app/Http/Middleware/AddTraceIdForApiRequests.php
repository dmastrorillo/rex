<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;


class AddTraceIdForApiRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {


        $traceId = Str::uuid()->toString();

        $request->attributes->set('trace_id', $traceId);
        $response = $next($request);
        $response->headers->set('X-Trace-Id', $traceId);
        return $response;
    }
}
