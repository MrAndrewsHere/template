<?php

declare(strict_types=1);

namespace App\Providers;

use App\Service\DealService;
use App\Service\Decorators\CachedDealService;
use App\Service\Interfaces\DealServiceInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
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
        $this->registerServices();

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::preventLazyLoading(! app()->isProduction());
        // JsonResource::withoutWrapping();
        $this->schedule();

        $this->configureCacheHeaders();

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

    private function registerServices(): void
    {
        $this->app->bind(
            DealServiceInterface::class,
            DealService::class,
        );

        if (config('cache.deals_service.enabled', true)) {
            $this->app->extend(DealServiceInterface::class, function ($service): CachedDealService {
                return new CachedDealService($service);
            });
        }
    }

    private function configureCacheHeaders(): void
    {
        if (config('app.enable_cache_headers')) {
            $options = collect(config('app.cache_headers_options', []))->join(';');

            if (! $options) {
                return;
            }

            Route::pushMiddlewareToGroup('api', "cache.headers:{$options}");
        }
    }
}
