<?php

namespace Exhum4n\Components\Console\Commands;

use Exhum4n\Components\Console\CmakeCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;

class FactoryCmakeCommand extends CmakeCommand
{
    /**
     * @var string
     */
    protected $name = 'cmake:factory';

    /**
     * @var string
     */
    protected $description = 'Create a new factory class';

    protected function getClassType(): string
    {
        return 'Factory';
    }

    protected function getRelativeNamespace(): string
    {
        return 'Database\Factories';
    }

    protected function getStub(): string
    {
        return $this->resolveStubPath('/stubs/factory/factory.stub');
    }

    protected function executeCommand(): int
    {
        if ($this->option('model') === null) {
            $this->error('--model (-m) option required!');

            return Command::INVALID;
        }

        return parent::executeCommand();
    }

    protected function getReplaces(): array
    {
        $model = $this->option('model');
        $modelNamespace = $this->namespaceBuilder->createNamespace("Models\\$model");

        return [
            '{{ class }}' => $this->namespaceBuilder->getClassName(),
            '{{ namespace }}' => $this->namespaceBuilder->getNamespace(),
            '{{ model }}' => $model,
            '{{ modelNamespace }}' => $modelNamespace
        ];
    }

    protected function addOptions(): array
    {
        return [
            [
                'model',
                'm',
                InputOption::VALUE_REQUIRED,
                'The name of the model'
            ],
        ];
    }
}
