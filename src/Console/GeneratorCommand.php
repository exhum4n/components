<?php

declare(strict_types=1);


namespace Exhum4n\Components\Console;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\App;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

abstract class GeneratorCommand extends Command
{
    /*
     * Аналог абстрактного объявления для константы:
     * https://stackoverflow.com/a/43134924/7939675
     * */
    protected const TYPE = self::TYPE;
    protected const STUB_PATH = self::STUB_PATH;

    protected Filesystem $files;
    protected NamespaceBuilder $namespaceBuilder;
    protected ClassBuilder $classBuilder;

    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;

        $this->classBuilder = App::make(ClassBuilder::class, [
            'stub' => $this->getStubContent()
        ]);
    }

    public function handle()
    {
        $this->namespaceBuilder = $this->getNamespaceBuilder();
        $classPath = $this->namespaceBuilder->getClassPath();

        $classContent = $this->classBuilder->getClassContent($this->replaces());

        $this->processIfClassAlreadyExistsAndNoForceOption($classPath);
        $this->makeDirectory($classPath);
        $this->files->put($classPath, $classContent);

        $this->info(static::TYPE . ' created successfully.');
    }

    abstract function relativeNamespace(): string;

    protected function getNamespaceBuilder(): NamespaceBuilder
    {
        return App::make(NamespaceBuilder::class, [
            'componentName' => $this->argument('component'),
            'relativeNamespace' => $this->relativeNamespace(),
            'className' => $this->argument('name')
        ]);
    }

    abstract protected function getStub(): string;

    protected function getStubContent(): string
    {
        $stubPath = $this->getStub();

        try {
            return $this->files->get($stubPath);
        } catch (FileNotFoundException $e) {
            $this->error($e->getMessage());

            exit(Command::FAILURE);
        }
    }

    protected function resolveStubPath($relativePath): string
    {
        $relativePath = trim($relativePath, '/');
        $publishedAbsolutePath = base_path($relativePath);

        return file_exists($publishedAbsolutePath)
            ? $publishedAbsolutePath
            : __DIR__ . $relativePath;
    }

    abstract public function replaces(): array;

    protected function processIfClassAlreadyExistsAndNoForceOption($classPath): void
    {
        if (
            (!$this->hasOption('force') || !$this->option('force')) &&
            $this->alreadyExists($classPath)
        ) {
            $this->error(static::TYPE . ' already exists! Use --force option to rewrite.');

            exit(Command::FAILURE);
        }
    }

    protected function alreadyExists($classPath): bool
    {
        return $this->files->exists($classPath);
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
            ['name', InputArgument::REQUIRED, 'The name of the ' . strtolower(static::TYPE)],
            ['component', InputArgument::REQUIRED, 'The name of the component'],
        ];
    }

    protected function getOptions(): array
    {
        return [
            ['force', null, InputOption::VALUE_NONE, 'Create the ' . strtolower(static::TYPE) . ' even if it already exists']
        ];
    }
}
