<?php

declare(strict_types=1);

use App\Domain\Share\Exceptions\JsonErrorHelper;
use App\Domain\Share\Middlewares\LogRequest;
use App\Http\Middleware\JsonRequestMiddleware;
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

        $middleware->alias([
            'json' => JsonRequestMiddleware::class,
        ]);

        $middleware->api(prepend: [
            JsonRequestMiddleware::class,
            LogRequest::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        JsonErrorHelper::handle($exceptions);
    })->create();
