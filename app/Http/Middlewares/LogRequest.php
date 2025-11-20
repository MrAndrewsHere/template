<?php

declare(strict_types=1);

namespace App\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogRequest
{
    public function handle(Request $request, Closure $next): Response
    {
        $request->attributes->set('request_start_time', microtime(true));

        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {

        $startTime = $request->attributes->get('request_start_time', microtime(true));

        Log::channel('requests')->info('Request', [
            'route_name' => $request->route()->getName(),
            'method' => $request->method(),
            'uri' => $request->getPathInfo(),
            'ip' => $request->ip(),
            'payload' => $request->all(),
            'headers' => $request->headers->all(),
        ]);

        Log::channel('requests')->info('Request terminated', [
            'route_name' => $request->route()->getName(),
            'url' => $request->getPathInfo(),
            'method' => $request->method(),
            'status' => $response->getStatusCode(),
            'duration_ms' => format_duration(microtime(true) - $startTime),
            'memory' => round(memory_get_peak_usage(true) / 1024 / 1024, 2).' MB',
        ]);
    }
}
