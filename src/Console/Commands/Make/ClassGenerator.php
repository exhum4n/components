<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace Exhum4n\Components\Console\Commands\Make;

use Exhum4n\Components\Exceptions\NotFoundException;
use Illuminate\Support\Pluralizer;

abstract class ClassGenerator extends Generator
{
    protected string $class_prefix = '';
    protected string $component;

    abstract protected function getStubName(): string;
    abstract protected function getTargetFolder(): string;
    abstract protected function getStubVariables(): array;

    public function handle(): void
    {
        parent::handle();

        $this->selectComponent();

        $this->make($this->component);
    }

    protected function make(string $component): void
    {
        $contents = $this->fillStub($this->getStubVariables());

        $targetPath = $this->getTargetPath($component);

        create_directory($targetPath);

        $filename = $this->getFileName($this->getClassname());

        write_file($targetPath, $filename, $contents);
    }

    protected function getClassName(?bool $withPrefix = true): string
    {
        $classname = ucwords(Pluralizer::singular($this->component));
        if ($withPrefix === false) {
            return $classname;
        }

        return ucwords($classname . $this->class_prefix);
    }

    protected function getPluralName($name, ?bool $uc = true): string
    {
        $plural = Pluralizer::plural($name);

        if ($uc) {
            return ucfirst($plural);
        }

        return $plural;
    }

    protected function getNamespace(): string
    {
        $componentDir = ucfirst($this->getComponentPath($this->component));

        $targetFolder = $this->getTargetFolder();

        return $componentDir . DIRECTORY_SEPARATOR . $targetFolder;
    }

    protected function getFileName(string $classname): string
    {
        return $classname . '.php';
    }

    private function getStub(): string
    {
        return file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . $this->getStubName());
    }

    private function getTargetPath(string $component): string
    {
        $componentPath = $this->getComponentPath($component);

        $basePath = base_path($componentPath);

        $targetFolder = $this->getTargetFolder();

        return $basePath . DIRECTORY_SEPARATOR . $targetFolder;
    }

    private function fillStub(array $variables): string
    {
        $stub = $this->getStub();

        foreach ($variables as $name => $value) {
            $stub = str_replace('{{ '. $name .' }}' , $value, $stub);
        }

        return $stub;
    }

    /**
     * @throws NotFoundException
     */
    private function selectComponent(): void
    {
        $components = $this->getComponentsNames();

        if (count($components) === 0 && $this->hasComponentOption() === false) {
            throw new NotFoundException("First need to create a component");
        }

        if ($this->hasComponentOption() === false) {
            $this->component = $this->choice('Choice a component', $components);

            return;
        }

        $component = $this->option('component');

        if (in_array(strtolower($component), $components) === false && $this->hasOption('force') === false) {
            throw new NotFoundException("Component with name '$component' has not found");
        }

        $this->component = $component ?: ucfirst($this->name);
    }

    protected function hasComponentOption(): bool
    {
        return empty($this->option('component')) === false;
    }
}
