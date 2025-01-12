<?php

namespace RBFraphael\LaravelBoilerplate\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeActionCommand extends GeneratorCommand
{
    protected $signature = 'make:action {name : Action name}';
    protected $description = 'Creates a new Action class';
    protected $type = 'Action';

    protected function getStub()
    {
        return __DIR__ . "/stubs/action.stub";
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Actions';
    }

    protected function getPath($name)
    {
        $name = $this->getNameInput();
        return app_path("Actions/{$name}.php");
    }
}
