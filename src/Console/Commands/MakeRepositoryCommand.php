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
            if($this->option('resource')){
                return __DIR__ . '/stubs/repository-model-resource.stub';
            }
            return __DIR__ . '/stubs/repository-model.stub';
        }

        return __DIR__ . '/stubs/repository.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Http\Repositories';
    }

    protected function getPath($name)
    {
        $name = $this->getNameInput();
        return app_path("Http/Repositories/{$name}.php");
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

        // Verifica se o parâmetro --resource foi passado
        if ($resource = $this->option('resource')) {
            $resourceClass = $this->qualifyModel($resource); // Obtém o namespace completo do resource
            $stub = str_replace('{{ resource }}', $resourceClass, $stub);
        }

        return $stub;
    }
}
