<?php

namespace RBFraphael\LaravelBoilerplate\Providers;

use Illuminate\Support\ServiceProvider;
use RBFraphael\LaravelBoilerplate\Console\Commands\MakeActionCommand;
use RBFraphael\LaravelBoilerplate\Console\Commands\MakeEnumCommand;
use RBFraphael\LaravelBoilerplate\Console\Commands\MakeRepositoryCommand;
use RBFraphael\LaravelBoilerplate\Console\Commands\MakeStringEnumCommand;

class LaravelServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands([
            MakeRepositoryCommand::class,
            MakeActionCommand::class,
            MakeEnumCommand::class,
            MakeStringEnumCommand::class,
        ]);
    }

    public function boot()
    {
        //
    }
    
}
