<?php

namespace RBFraphael\LaravelBoilerplate;

use Illuminate\Support\ServiceProvider;
use RBFraphael\LaravelBoilerplate\Console\Commands\MakeRepositoryCommand;

class LaravelBoilerplateServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands([
            MakeRepositoryCommand::class,
        ]);
    }

    public function boot()
    {
        //
    }
    
}
