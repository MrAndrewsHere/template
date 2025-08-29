<?php

\Illuminate\Support\Facades\Route::get('/', function () {
    return app()->version();
});
