<?php

namespace App\Service\Interfaces;

use App\Models\User;
use Illuminate\Contracts\Pipeline\Pipeline;

interface TaskPipelineInterface extends Pipeline
{
    public function handleStatus(): static;

    public function assignManager(): static;

    public function save(): static;

    public function highPriority(): static;

    public function statusChanged(): static;

    public function commentCompleted(User $user): static;
}
