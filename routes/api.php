<?php

use App\Http\Controllers\TaskController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return app()->version();
});

Route::prefix('v1')->middleware(['api'])->group(function (Router $route) {

    $route->get('/', fn () => app()->version());

    $route->apiResource('tasks', TaskController::class)
        ->only(['index', 'store', 'show']);

    $route->put('tasks/{task}/status', [TaskController::class, 'status'])->name('tasks.status.update');

    $route->post('tasks/{task}/comments', [TaskController::class, 'comment'])->name('tasks.comments.store');
});
