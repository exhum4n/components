<?php

declare(strict_types=1);

namespace Exhum4n\Components\Console;

use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\InflectorFactory;

class NamespaceBuilder
{
    protected const ROOT_NAMESPACE = 'Components';
    private string $component;
    private string $relativeNamespace;
    private string $className;

    protected Inflector $inflector;

    public function __construct(string $componentName, string $relativeNamespace, string $className)
    {
        $this->inflector = InflectorFactory::create()->build();

        $this->setComponent($componentName);
        $this->setRelativeNamespace($relativeNamespace);
        $this->setClassName($className);
    }

    public function getClassNamespace(): string
    {
        return $this->createNamespace($this->getRelativeNamespace(), $this->getClassName());
    }

    public function getClassPath(bool $returnRelativePath = false): string
    {
        $relativePath = $this->convertNamespaceToFilePath($this->getClassNamespace());

        if ($returnRelativePath) {
            return $relativePath;
        }

        return base_path($relativePath);
    }

    public function createNamespace(string $relativeNamespace, string $className): string
    {
        $path = [
            $this->getRootNamespace(),
            $this->getComponent(),
            $relativeNamespace,
            $className
        ];

        return implode('\\', $path);
    }

    public function createClassPath(string $relativeNamespace, string $className, bool $returnRelativePath = false): string
    {
        $namespace = $this->createNamespace($relativeNamespace, $className);
        $relativePath = $this->convertNamespaceToFilePath($namespace);

        if ($returnRelativePath) {
            return $relativePath;
        }

        return base_path($relativePath);
    }

    public function convertNamespaceToFilePath(string $namespace): string
    {
        $search = [
            '\\',
            $this->getRootNamespace(),
        ];

        $replace = [
            DIRECTORY_SEPARATOR,
            strtolower($this->getRootNamespace())
        ];

        return str_replace($search, $replace, $namespace) . '.php';
    }

    public function getRootNamespace(): string
    {
        return static::ROOT_NAMESPACE;
    }

    public function getComponent(): string
    {
        return $this->component;
    }

    protected function setComponent(string $component): void
    {
        $this->component = $this->inflector->pluralize($component);
        $this->component = $this->inflector->classify($this->component);
    }

    public function getRelativeNamespace(): string
    {
        return $this->relativeNamespace;
    }

    protected function setRelativeNamespace(string $relativeNamespace): void
    {
        $this->relativeNamespace = $this->inflector->classify($relativeNamespace);
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    protected function setClassName(string $className): void
    {
        $this->className = $this->inflector->singularize($className);
        $this->className = $this->inflector->classify($this->className);
    }
}
