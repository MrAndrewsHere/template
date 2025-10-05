<?php

declare(strict_types=1);

namespace App\Service\Interfaces;

use App\Models\Task;
use App\Service\DTOs\Task\TaskDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TaskServiceInterface
{
    /**
     * Display a listing of the resource.
     */
    public function index(): LengthAwarePaginator;

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskDTO $DTO): Task;

    /**
     * Display the specified resource.
     */
    public function show(Task $task): Task;

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskDTO $DTO, Task $task): Task;

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task): void;
}
