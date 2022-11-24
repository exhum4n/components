<?php

declare(strict_types=1);

namespace Exhum4n\Components\Console\Commands\Make;

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

        $this->component = $this->choice('Choice a component', $this->getComponentsNames());

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
        return file_get_contents(__DIR__ . '/stubs/' . $this->getStubName());
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
}
