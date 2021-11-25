<?php

declare(strict_types=1);

namespace Exhum4n\Components\Console;

use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\InflectorFactory;
use LogicException;

class NamespaceBuilder
{
    protected const ROOT_NAMESPACE = 'Components';
    private string $component;
    private string $relativeNamespace;
    private string $className;

    protected Inflector $inflector;

    public function __construct(string $componentName, string $relativeNamespace, string $className)
    {
        $this->component = $componentName;
        $this->relativeNamespace = $relativeNamespace;
        $this->className = $className;

        $this->inflector = InflectorFactory::create()->build();
    }

    public function getRootNamespace(): string
    {
        return static::ROOT_NAMESPACE;
    }

    public function getComponent(): string
    {
        return $this->component;
    }

    public function getRelativeNamespace(): string
    {
        return $this->relativeNamespace;
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function getNamespace(): string
    {
        return $this->createNamespace($this->relativeNamespace);
    }

    public function createNamespace(string $relativeNamespace): string
    {
        $path = [
            static::ROOT_NAMESPACE,
            $this->component,
            $relativeNamespace
        ];

        return implode('\\', $path);
    }

    public function getNamespaceAndClassName(): string
    {
        return "{$this->getNamespace()}\\$this->className";
    }

    public function getClassPath(bool $relative = false): string
    {
        $relativePath = $this->convertNamespaceToFilePath();
        if ($relative) {
            return $relativePath;
        }

        return base_path($relativePath);
    }

    protected function convertNamespaceToFilePath(): string
    {
        $search = [
            '\\',
            $this->getRootNamespace(),
        ];

        $replace = [
            DIRECTORY_SEPARATOR,
            strtolower($this->getRootNamespace())
        ];

        $replacedNamespace = str_replace($search, $replace, $this->getNamespace());

        return $replacedNamespace . DIRECTORY_SEPARATOR . "$this->className.php";
    }

    public function getMigrationPath(bool $relative = false): string
    {
        $pathWithoutFileName = preg_replace('~^(.*)/.*$~', '$1', $this->getClassPath($relative));
        $migrationPrefix = date('Y_m_d_His');
        $migrationName = $this->inflector->tableize($this->getClassName());
        $fileName = "{$migrationPrefix}_$migrationName.php";

        return $pathWithoutFileName . DIRECTORY_SEPARATOR . $fileName;
    }

    public function getNamespacedUserProviderModel(?string $guard = null)
    {
        $defaultGuard = ($guard) ?: config('auth.defaults.guard');
        $provider = config("auth.guards.$defaultGuard.provider");
        if ($provider === null) {
            throw new LogicException("The [$defaultGuard] guard is not defined in your \"auth\" configuration file.");
        }

        return config("auth.providers.$provider.model");
    }

    public function getClassFromNamespace(string $namespace): string
    {
        return preg_replace('~^.*\\\(.*)$~', '$1', $namespace);
    }
}
