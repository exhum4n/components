<?php

namespace Exhum4n\Components\Console\Commands\Make;

class Component extends Generator
{
    protected const COMPONENTS_DIR = 'components';

    protected function getActionName(): string
    {
        return 'make {name : The name of component that will be created}';
    }

    public function handle(): void
    {
        parent::handle();

        $componentsPath = $this->createComponentDir();

        $this->processDatabase($componentsPath);
        $this->processConsole($componentsPath);
        $this->processBroadcasting($componentsPath);
        $this->processData($componentsPath);
        $this->processEnums($componentsPath);
        $this->processExceptions($componentsPath);
        $this->processJobs($componentsPath);
        $this->processModels($componentsPath);
        $this->processProviders($componentsPath);
        $this->processRepositories($componentsPath);
        $this->processServices($componentsPath);
        $this->processRoutes($componentsPath);
        $this->processTraits($componentsPath);

        $this->call("components:provider", [
            'name' => $this->getName()
        ]);
    }

    protected function processDatabase(string $componentsPath): void
    {
        $path = $this->createDir($componentsPath, 'Database');

        $this->createDir($path, 'Migrations');
        $this->createDir($path, 'Seeds');
        $this->createDir($path, 'Factories');
    }

    protected function processConsole(string $componentsPath): void
    {
        $this->createDir($componentsPath, 'Commands');
    }

    protected function processBroadcasting(string $componentsPath): void
    {
        $root = $this->createDir($componentsPath, 'Broadcasting');

        $this->createDir($root, 'Events');
        $this->createDir($root, 'Listeners');
    }

    protected function processData(string $componentsPath): void
    {
        $this->createDir($componentsPath, 'Data');
    }

    protected function processEnums(string $componentsPath): void
    {
        $this->createDir($componentsPath, 'Enums');
    }

    protected function processExceptions(string $componentsPath): void
    {
        $this->createDir($componentsPath, 'Exceptions');
    }

    protected function processJobs(string $componentsPath): void
    {
        $this->createDir($componentsPath, 'Jobs');
    }

    protected function processModels(string $componentsPath): void
    {
        $path = $this->createDir($componentsPath, 'Models');

        $this->createDir($path, 'Casts');
    }

    protected function processProviders(string $componentsPath): void
    {
        $this->createDir($componentsPath, 'Providers');
    }

    protected function processRepositories(string $componentsPath): void
    {
        $this->createDir($componentsPath, 'Repositories');
    }

    protected function processServices(string $componentsPath): void
    {
        $this->createDir($componentsPath, 'Services');
    }

    protected function processRoutes(string $componentsPath): void
    {
        $this->createDir($componentsPath, 'Routes');
    }

    protected function processTraits(string $componentsPath): void
    {
        $this->createDir($componentsPath, 'Traits');
    }

    protected function createComponentDir(): string
    {
        return $this->createDir(base_path(static::COMPONENTS_DIR), $this->getName());
    }

    private function createDir($path, string $name): string
    {
        return create_directory($path . DIRECTORY_SEPARATOR . $name);
    }
}
