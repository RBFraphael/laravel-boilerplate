<?php

namespace RBFraphael\LaravelBoilerplate;

use Illuminate\Support\ServiceProvider;

class LaravelBoilerplateServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Registre bindings ou singletons aqui.
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Carregar arquivos, rotas, views, configurações, etc.
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'mypackage');
        $this->publishes([
            __DIR__ . '/../config/mypackage.php' => config_path('mypackage.php'),
        ]);
    }
}
