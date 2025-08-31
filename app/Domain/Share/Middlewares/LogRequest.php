<?php

declare(strict_types=1);

namespace App\Domain\Share\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class LogRequest
{
    /**
     * @throws Throwable
     */
    public function handle(Request $request, Closure $next): Response
    {
        $route = $request->route();

        Log::channel('requests')->info('Request', [
            'route_name' => $route->getName(),
            'method' => $request->method(),
            'uri' => $request->getPathInfo(),
            'payload' => $request->all(),
            'headers' => $request->headers->all(),
            'ip' => $request->ip(),
        ]);

        return $next($request);
    }
}
