<?php

declare(strict_types=1);

namespace Exhum4n\Components\Console;

use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\InflectorFactory;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\App;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

abstract class CmakeCommand extends Command
{
    protected string $type;

    protected Inflector $inflector;
    protected Filesystem $files;
    protected NamespaceBuilder $namespaceBuilder;
    protected ClassBuilder $classBuilder;

    public function handle(): int
    {
        $this->resolveDependencies();

        $exitCode = $this->executeCommand();

        if ($exitCode === static::SUCCESS) {
            $this->info("{$this->getClassType()} created successfully.");
        }

        return $exitCode;
    }

    protected function executeCommand(): int
    {
        if ($this->getClassType() === 'Migration') {
            $classPath = $this->namespaceBuilder->getMigrationPath();
        } else {
            $classPath = $this->namespaceBuilder->getClassPath();
        }

        $classContent = $this->classBuilder->getClassContent($this->getReplaces());

        $this->checkIfClassExists($classPath);

        $this->makeDirectory($classPath);

        $this->files->put($classPath, $classContent);

        return static::SUCCESS;
    }

    protected function resolveDependencies(): void
    {
        $this->inflector = InflectorFactory::create()->build();

        $this->files = App::make(Filesystem::class);

        $this->namespaceBuilder = App::make(NamespaceBuilder::class, [
            'componentName' => $this->argument('component'),
            'relativeNamespace' => $this->getRelativeNamespace(),
            'className' => $this->argument('name')
        ]);

        $this->classBuilder = App::make(ClassBuilder::class, [
            'stub' => $this->getStubContent()
        ]);
    }

    abstract protected function getClassType(): string;

    abstract protected function getRelativeNamespace(): string;

    abstract protected function getStub(): string;

    abstract protected function getReplaces(): array;

    protected function getStubContent(): string
    {
        $stubPath = $this->getStub();

        try {
            return $this->files->get($stubPath);
        } catch (FileNotFoundException $e) {
            $this->error($e->getMessage());

            exit(static::FAILURE);
        }
    }

    protected function resolveStubPath($relativePath): string
    {
        $relativePath = trim($relativePath, '/');
        $publishedAbsolutePath = base_path($relativePath);

        return file_exists($publishedAbsolutePath)
            ? $publishedAbsolutePath
            : __DIR__ . DIRECTORY_SEPARATOR . $relativePath;
    }

    protected function checkIfClassExists($classPath): void
    {
        if (!$this->option('force') && $this->files->exists($classPath)) {
            $this->error("{$this->getClassType()} already exists! Use --force option to rewrite.");

            exit(static::FAILURE);
        }
    }

    protected function makeDirectory($path)
    {
        if (!$this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0755, true, true);
        }

        return $path;
    }

    protected function getArguments(): array
    {
        return [
            [
                'name',
                InputArgument::REQUIRED,
                'The name of the ' . strtolower($this->getClassType())
            ],
            [
                'component',
                InputArgument::REQUIRED,
                'The name of the component'
            ]
        ];
    }

    protected function defaultOptions(): array
    {
        return [
            [
                'force',
                null,
                InputOption::VALUE_NONE,
                'Create the ' . strtolower($this->getClassType()) . ' even if it already exists'
            ]
        ];
    }

    protected function getOptions(): array
    {
        return array_merge(
            method_exists($this, 'addOptions') ? $this->addOptions() : [],
            $this->defaultOptions()
        );
    }
}
