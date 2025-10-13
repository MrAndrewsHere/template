<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Models\Task;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasBelongsToTask
{
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
