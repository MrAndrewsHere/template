<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Service\DTOs\Task\TaskDTO;
use App\Service\Interfaces\TaskServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

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
        Gate::authorize('view', $task);

        return TaskResource::make(
            $this->service->show($task))
            ->response();
    }

    public function update(TaskRequest $updateTaskRequest, Task $task): JsonResponse
    {
        Gate::authorize('update', $task);

        return TaskResource::make(
            $this->service->update(TaskDTO::from($updateTaskRequest->validated()), $task))
            ->response();
    }

    public function destroy(Task $task): JsonResponse
    {
        Gate::authorize('delete', $task);

        $this->service->destroy($task);

        return response()->json(status: 204);
    }
}
