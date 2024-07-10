<?php

namespace App\Providers;

use HansSchouten\LaravelPageBuilder\ServiceProvider as BaseServiceProvider;
use Illuminate\Support\Facades\Schema;
use HansSchouten\LaravelPageBuilder\Commands\CreateTheme;
use HansSchouten\LaravelPageBuilder\Commands\PublishDemo;
use HansSchouten\LaravelPageBuilder\Commands\PublishTheme;
use PHPageBuilder\PHPageBuilder;
use Exception;

class CustomPageBuilderServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     * @throws Exception
     */
    public function boot()
    {
        // No llamar a parent::boot() para evitar el registro automático de rutas
        $this->loadMigrationsFrom(__DIR__ . '/../../vendor/hansschouten/laravel-pagebuilder/migrations');

        if (Schema::hasTable(config('pagebuilder.storage.database.prefix').'settings')) {
            if ($this->app->runningInConsole()) {
                $this->commands([
                    CreateTheme::class,
                    PublishTheme::class,
                    PublishDemo::class,
                ]);
            } elseif (empty(config('pagebuilder'))) {
                throw new Exception("No PHPageBuilder config found, please run: php artisan vendor:publish --provider=\"HansSchouten\LaravelPageBuilder\ServiceProvider\" --tag=config");
            }

            // register singleton phpPageBuilder (this ensures phpb_ helpers have the right config without first manually creating a PHPageBuilder instance)
            $this->app->singleton('phpPageBuilder', function($app) {
                return new PHPageBuilder(config('pagebuilder') ?? []);
            });
            $this->app->make('phpPageBuilder');

            $this->publishes([
                __DIR__ . '/../../vendor/hansschouten/laravel-pagebuilder/config/pagebuilder.php' => config_path('pagebuilder.php'),
            ], 'config');
            $this->publishes([
                __DIR__ . '/../../vendor/hansschouten/laravel-pagebuilder/themes/demo' => base_path(config('pagebuilder.theme.folder_url') . '/demo'),
            ], 'demo-theme');
        }
    }
}
