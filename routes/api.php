<?php

use App\Http\Controllers\DealController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->middleware('api')
    ->group(function (Router $router) {

        $router->get('/', function () {
            return response()->json([
                'message' => 'API v1',
                'app_version' => app()->version(),
            ]);
        })->name('api');

        $router->apiResource('deals', DealController::class);

    });
