<?php

declare(strict_types=1);

namespace App\Providers;

use App\Jobs\Test;
use App\Models\Task;
use App\Models\User;
use App\Policies\TaskPolicy;
use App\Service\Interfaces\TaskServiceInterface;
use App\Service\TaskService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
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
        $this->schedule();

        Gate::policy(Task::class, TaskPolicy::class);

        $this->app->bind(TaskServiceInterface::class, function (): TaskService {
            return new TaskService(Auth::check() ? request()->user() : new User);
        });

    }

    private function registerTelescopeLocally(): void
    {
        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    private function schedule(): void
    {
        //        Schedule::job(new Test)->everyMinute();

        Schedule::command('pulse:check')->everyMinute();

        Schedule::command('pulse:ingest')->everyMinute();

        Schedule::command('telescope:prune --hours=72')->daily();

    }
}
