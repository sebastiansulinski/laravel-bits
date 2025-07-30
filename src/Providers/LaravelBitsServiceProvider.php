<?php

namespace LaravelBits\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use LaravelBits\Macros\EloquentBuilderUpdateMany;
use LaravelBits\Macros\QueryBuilderWhereDateBetween;

class LaravelBitsServiceProvider extends ServiceProvider
{
    protected array $macros = [
        QueryBuilderWhereDateBetween::class,
        EloquentBuilderUpdateMany::class,
    ];

    /**
     * Bootstrap any package services.
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../config/laravel-bits.php' => config_path('laravel-bits.php'),
        ], 'laravel-bits');

        $this->registerMacros();

        Model::shouldBeStrict(! $this->app->isProduction());
    }

    /**
     * Register macros.
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function registerMacros(): void
    {
        collect($this->macros)->each(
            fn (string $class) => $this->app->make($class)->register()
        );
    }

    /**
     * Register bindings.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/laravel-bits.php',
            'laravel-bits'
        );
    }
}
