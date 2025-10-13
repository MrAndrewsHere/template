<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Service\Interfaces\OverdueServiceInterface;
use Illuminate\Console\Command;

class CheckOverdue extends Command
{
    /**
     * @var string
     */
    protected $signature = 'tasks:check-overdue {--dry-run : Show what would be done without making changes}';

    /**
     * @var string
     */
    protected $description = 'To Comment and notify overdue tasks';

    public function handle(): int
    {
        $service = app()->make(OverdueServiceInterface::class);

        $count = $this->option('dry-run') ? $service->count() : $service->handle();

        $this->info(__('task.check-overdue', ['count' => $count]));

        return Command::SUCCESS;
    }
}
