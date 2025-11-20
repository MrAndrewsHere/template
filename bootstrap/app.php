<?php

declare(strict_types=1);

use App\Domain\Share\Exceptions\JsonErrorHelper;
use App\Http\Middlewares\LogRequest;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(prepend: [LogRequest::class]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        JsonErrorHelper::handle($exceptions);
    })->create();
