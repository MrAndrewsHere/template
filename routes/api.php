<?php

use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return app()->version();
});

Route::prefix('v1')->middleware('auth:sanctum')->group(function ($route) {

    $route->get('/', fn () => app()->version());

    $route->get('/user', function (Request $request) {
        return $request->user();
    })->name('user');

    $route->apiResource('tasks', TaskController::class);
});
