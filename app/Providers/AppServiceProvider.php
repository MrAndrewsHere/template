<?php

namespace App\Providers;

use App\Jobs\Test;
use Illuminate\Database\Eloquent\Model;
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
        $this->schedule();
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
        Schedule::job(new Test)->everyMinute();

        Schedule::command('pulse:check')->everyMinute();

        Schedule::command('pulse:ingest')->everyMinute();

        Schedule::command('telescope:prune --hours=72')->daily();

    }
}
