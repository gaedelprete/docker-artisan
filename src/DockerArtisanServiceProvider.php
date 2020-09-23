<?php

namespace GaeDelPrete\DockerArtisan;

use GaeDelPrete\DockerArtisan\Commands\DockerInitCommand;
use GaeDelPrete\DockerArtisan\Commands\DockerStartCommand;
use GaeDelPrete\DockerArtisan\Commands\DockerStopCommand;
use Illuminate\Support\ServiceProvider;

class DockerArtisanServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'docker-artisan');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'docker-artisan');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('docker-artisan.php'),
            ], 'docker-artisan');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/docker-artisan'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/docker-artisan'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/docker-artisan'),
            ], 'lang');*/

            // Registering package commands.
            $this->commands([
                DockerInitCommand::class,
                DockerStartCommand::class,
                DockerStopCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'docker-artisan');

        // Register the main class to use with the facade
        $this->app->singleton('docker-artisan', function () {
            return new DockerArtisan;
        });
    }
}
