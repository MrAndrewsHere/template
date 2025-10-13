<?php

use App\Service\Interfaces\TaskServiceInterface;

if (! function_exists('taskService')) {
    function taskService(): TaskServiceInterface
    {
        return app()->make(TaskServiceInterface::class);
    }
}
