<?php

namespace Exhum4n\Components\Console\Commands;

use Illuminate\Database\Console\Factories\FactoryMakeCommand;
use Illuminate\Support\Str;

class FactoryCmakeCommand extends FactoryMakeCommand
{
    use ComponentGenerator;

    protected $name = 'cmake:factory';

    protected string $relatedNamespace = '\Database\Factories';

    protected array $brokenOptions = [
        'model',
    ];

    /**
     * @param $name
     *
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name): string
    {
        if (!$this->option('component')) {
            parent::buildClass($name);
        }

        $factory = class_basename(Str::ucfirst(str_replace('Factory', '', $name)));

        $namespaceModel = $this->option('model')
            ? $this->qualifyModel($this->option('model'))
            : $this->qualifyModel($this->guessModelName($name));

        $model = class_basename($namespaceModel);

        if (Str::startsWith($namespaceModel, $this->rootNamespace() . 'Models')) {
            $namespace = Str::beforeLast('Database\\Factories\\' . Str::after($namespaceModel, $this->rootNamespace() . 'Models\\'), '\\');
        } else {
            $namespace = $this->rootNamespace() . $this->option('component') . '\Database\\Factories';
        }

        $replace = [
            '{{ factoryNamespace }}' => $namespace,
            'NamespacedDummyModel' => $namespaceModel,
            '{{ namespacedModel }}' => $namespaceModel,
            '{{namespacedModel}}' => $namespaceModel,
            'DummyModel' => $model,
            '{{ model }}' => $model,
            '{{model}}' => $model,
            '{{ factory }}' => $factory,
            '{{factory}}' => $factory,
        ];

        $stub = $this->files->get($this->getStub());

        return str_replace(
            array_keys($replace), array_values($replace), $this->replaceNamespace($stub, $name)->replaceClass($stub, $name)
        );
    }
}
