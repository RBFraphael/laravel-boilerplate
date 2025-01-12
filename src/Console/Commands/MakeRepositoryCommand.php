<?php

namespace RBFraphael\LaravelBoilerplate\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeRepositoryCommand extends GeneratorCommand
{
    protected $signature = 'make:repository {name : Repository name} {--model= : Related model}';
    protected $description = 'Creates a new Repository class';
    protected $type = 'Repository';

    protected function getStub()
    {
        if ($this->option('model')) {
            return __DIR__ . '/stubs/repository-model.stub';
        }

        return __DIR__ . '/stubs/repository.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Repositories';
    }

    protected function getPath($name)
    {
        $name = $this->getNameInput();
        return app_path("Repositories/{$name}.php");
    }

    protected function buildClass($name)
    {
        // Gera o conteúdo base do stub
        $stub = parent::buildClass($name);

        // Verifica se o parâmetro --model foi passado
        if ($model = $this->option('model')) {
            $modelClass = $this->qualifyModel($model); // Obtém o namespace completo do modelo
            $stub = str_replace('{{ model }}', $modelClass, $stub);
        }

        return $stub;
    }
}
