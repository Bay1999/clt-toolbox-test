<?php

namespace App\Providers;

use App\Interfaces\SupplierInterface;
use App\Repositories\SupplierRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }
        $this->app->bind(
            SupplierInterface::class,
            SupplierRepository::class
        );
        $this->app->bind(
            \App\Interfaces\CltLayupInterface::class,
            \App\Repositories\CltLayupRepository::class
        );
        $this->app->bind(
            \App\Interfaces\CltLayerInterface::class,
            \App\Repositories\CltLayerRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
