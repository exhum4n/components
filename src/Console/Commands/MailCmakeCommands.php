<?php

namespace Exhum4n\Components\Console\Commands;

use Exhum4n\Components\Console\CmakeCommand;

class MailCmakeCommands extends CmakeCommand
{
    /**
     * @var string
     */
    protected $name = 'cmake:mail';

    /**
     * @var string
     */
    protected $description = 'Create a new email class';

    protected function getClassType(): string
    {
        return 'Mail';
    }

    protected function getRelativeNamespace(): string
    {
        return 'Mail';
    }

    protected function getStub(): string
    {
        return $this->resolveStubPath('/stubs/mail/mail.stub');
    }

    protected function getReplaces(): array
    {
        return [
            '{{ class }}' => $this->namespaceBuilder->getClassName(),
            '{{ namespace }}' => $this->namespaceBuilder->getNamespace()
        ];
    }
}
