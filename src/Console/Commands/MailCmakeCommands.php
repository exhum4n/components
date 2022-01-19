<?php

namespace Exhum4n\Components\Console\Commands;

use Exhum4n\Components\Console\CmakeCommand;

class MailCmakeCommands extends CmakeCommand
{
    protected $name = 'cmake:mail';
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
        return $this->resolveStubPath('/Stubs/mail/mail.stub');
    }

    protected function getReplaces(): array
    {
        return [
            '{{ class }}' => $this->namespaceBuilder->getClassName(),
            '{{ namespace }}' => $this->namespaceBuilder->getNamespace()
        ];
    }
}
