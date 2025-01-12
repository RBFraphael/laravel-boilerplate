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
        return app_path("Repositories/{$name}.php");
    }

    public function handle()
    {
        // Use o método original do GeneratorCommand para criar o arquivo
        $path = $this->getPath($this->qualifyClass($this->getNameInput()));

        // Verifica se o arquivo já existe
        if ($this->files->exists($path)) {
            $this->error('Repository already exists!');
            return false;
        }

        // Cria o arquivo
        $this->makeDirectory($path);
        $this->files->put($path, $this->sortImports($this->buildClass($this->getNameInput())));

        // Exibe a mensagem de sucesso
        $this->info("Repository [{$path}] created successfully.");
    }
}
