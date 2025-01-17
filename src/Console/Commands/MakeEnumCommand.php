<?php

namespace RBFraphael\LaravelBoilerplate\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeEnumCommand extends GeneratorCommand
{
    protected $signature = 'make:enum {name : Enum name}';
    protected $description = 'Creates a new Enum class';
    protected $type = 'Enum';

    protected function getStub()
    {
        return __DIR__ . "/stubs/enum.stub";
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Enums';
    }

    protected function getPath($name)
    {
        $name = $this->getNameInput();
        return app_path("Enums/{$name}.php");
    }
}
