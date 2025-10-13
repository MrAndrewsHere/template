<?php

declare(strict_types=1);

namespace App\Service\Tasks;

use App\Models\Task;
use App\Models\User;
use App\Service\Interfaces\TaskPipelineInterface;
use App\Service\Tasks\PipeHandlers\AssignManager;
use App\Service\Tasks\PipeHandlers\CommentCompleted;
use App\Service\Tasks\PipeHandlers\HandleStatus;
use App\Service\Tasks\PipeHandlers\HighPriority;
use App\Service\Tasks\PipeHandlers\Save;
use App\Service\Tasks\PipeHandlers\StatusChanged;
use Closure;
use Illuminate\Pipeline\Pipeline;

class TaskPipeline extends Pipeline implements TaskPipelineInterface
{
    public function handleStatus(): static
    {
        $this->pipe(HandleStatus::class);

        return $this;
    }

    public function assignManager(): static
    {
        $this->pipe(AssignManager::class);

        return $this;
    }

    public function save(): static
    {
        $this->pipe(Save::class);

        return $this;
    }

    public function highPriority(): static
    {
        $this->pipe(HighPriority::class);

        return $this;
    }

    public function statusChanged(): static
    {
        $this->pipe(StatusChanged::class);

        return $this;
    }

    public function commentCompleted(User $user): static
    {
        $this->pipe(new CommentCompleted($user));

        return $this;
    }

    public function then(Closure $destination): Task
    {
        return parent::then($destination);
    }
}
