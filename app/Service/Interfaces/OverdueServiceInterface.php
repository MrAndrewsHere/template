<?php

declare(strict_types=1);

namespace App\Service\Interfaces;

use App\Models\Task;

interface OverdueServiceInterface
{
    public function handle(): int;

    public function count(): int;
}
