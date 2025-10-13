<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JsonRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request):Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->expectsJson() && ! $request->isJson()) {
            return response()->json([
                'error' => __('Некорректный формат запроса'),
                'message' => __('Запрос должен быть в формате JSON'),
            ], 400);
        }

        return $next($request);
    }
}
