<?php

namespace RBFraphael\LaravelBoilerplate\Providers;

use Illuminate\Support\ServiceProvider;
use RBFraphael\LaravelBoilerplate\Console\Commands\MakeActionCommand;
use RBFraphael\LaravelBoilerplate\Console\Commands\MakeRepositoryCommand;

class LaravelServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands([
            MakeRepositoryCommand::class,
            MakeActionCommand::class,
        ]);
    }

    public function boot()
    {
        //
    }
    
}
