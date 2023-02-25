<?php

declare(strict_types=1);

namespace Abordage\LaravelOpenGraphImages;

use Illuminate\Support\ServiceProvider;

class OpenGraphImagesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            /* Publishing config */
            $this->publishes([
              __DIR__ . '/../config/og-images.php' => config_path('og-images.php'),
            ], 'og-images-config');
        }
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        /* Automatically apply the package configuration */
        $this->mergeConfigFrom(__DIR__ . '/../config/og-images.php', 'og-images');

        /* Register the main class to use with the facade */
        $this->app->singleton('laravel-og-images', function () {
            return new OpenGraphImages();
        });
    }
}
