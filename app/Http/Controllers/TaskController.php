<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskCommentRequest;
use App\Http\Requests\TaskRequest;
use App\Http\Requests\UpdateTaskStatusRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Service\DTOs\Task\TaskCommentDTO;
use App\Service\DTOs\Task\TaskDTO;
use App\Service\DTOs\Task\TaskStatusDTO;
use App\Service\Interfaces\TaskServiceInterface;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    public function __construct(public TaskServiceInterface $service) {}

    public function index(): JsonResponse
    {
        return TaskResource::collection(
            $this->service
                ->index()
                ->appends(request()->query()))
            ->response();
    }

    public function store(TaskRequest $storeTaskRequest): JsonResponse
    {
        return TaskResource::make(
            $this->service->store(TaskDTO::from($storeTaskRequest->validated())))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Task $task): JsonResponse
    {
        return TaskResource::make(
            $this->service->show($task))
            ->response();
    }

    public function status(UpdateTaskStatusRequest $request, Task $task): JsonResponse
    {
        $this->service->status(TaskStatusDTO::from($request->validated()), $task);

        return TaskResource::make(
            $this->service->show($task))
            ->response();
    }

    public function comment(StoreTaskCommentRequest $request, Task $task): JsonResponse
    {
        return CommentResource::make(
            $this->service->comment($task, TaskCommentDTO::from($request->validated())))
            ->response()
            ->setStatusCode(201);
    }
}
