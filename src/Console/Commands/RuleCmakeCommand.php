<?php

namespace Exhum4n\Components\Console\Commands;

use Exhum4n\Components\Console\CmakeCommand;
use Symfony\Component\Console\Input\InputOption;

class RuleCmakeCommand extends CmakeCommand
{
    /**
     * @var string
     */
    protected $name = 'cmake:rule';

    /**
     * @var string
     */
    protected $description = 'Create a new validation rule';

    protected function getClassType(): string
    {
        return 'Rule';
    }

    protected function getRelativeNamespace(): string
    {
        return 'Rules';
    }

    protected function getStub(): string
    {
        return $this->resolveStubPath('/stubs/rule/rule.stub');
    }

    protected function getReplaces(): array
    {
        $ruleType = $this->option('implicit') ? 'ImplicitRule' : 'Rule';

        return [
            '{{ class }}' => $this->namespaceBuilder->getClassName(),
            '{{ namespace }}' => $this->namespaceBuilder->getNamespace(),
            '{{ ruleType }}' => $ruleType
        ];
    }

    protected function addOptions(): array
    {
        return [
            [
                'implicit',
                'i',
                InputOption::VALUE_NONE,
                'Generate an implicit rule'
            ]
        ];
    }
}
