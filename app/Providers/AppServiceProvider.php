<?php

declare(strict_types=1);

namespace App\Providers;

use App\Notifications\Channels\LogChannel;
use App\Service\Interfaces\OverdueServiceInterface;
use App\Service\Interfaces\TaskNotificationServiceInterface;
use App\Service\Interfaces\TaskPipelineInterface;
use App\Service\Interfaces\TaskServiceInterface;
use App\Service\Tasks\CheckOverdueService;
use App\Service\Tasks\TaskNotificationService;
use App\Service\Tasks\TaskPipeline;
use App\Service\Tasks\TaskService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerTelescopeLocally();

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::preventLazyLoading(! app()->isProduction());

        JsonResource::withoutWrapping();

        $this
            ->bindServices()
            ->extendNotification()
            ->schedule();

    }

    private function registerTelescopeLocally(): void
    {
        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    private function schedule(): static
    {
        //        Schedule::job(new Test)->everyMinute();

        Schedule::command('pulse:check')->everyMinute();

        Schedule::command('pulse:ingest')->everyMinute();

        Schedule::command('telescope:prune --hours=72')->daily();

        return $this;

    }

    private function bindServices(): static
    {
        $this->app->bind(TaskServiceInterface::class, TaskService::class);
        $this->app->bind(TaskNotificationServiceInterface::class, TaskNotificationService::class);
        $this->app->bind(OverdueServiceInterface::class, CheckOverdueService::class);
        $this->app->bind(TaskPipelineInterface::class, TaskPipeline::class);

        return $this;
    }

    private function extendNotification(): static
    {
        Notification::extend('log', fn (): LogChannel => new LogChannel);

        return $this;
    }
}
