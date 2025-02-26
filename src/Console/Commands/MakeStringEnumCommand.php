<?php

namespace RBFraphael\LaravelBoilerplate\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeStringEnumCommand extends GeneratorCommand
{
    protected $signature = 'make:string-enum {name : string Enum name}';
    protected $description = 'Creates a new Enum class for string values';
    protected $type = 'stringEnum';

    protected function getStub()
    {
        return __DIR__ . "/stubs/string-enum.stub";
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
