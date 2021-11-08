<?php

namespace Exhum4n\Components\Console\Commands;

use Illuminate\Foundation\Console\MailMakeCommand;

class MailCmakeCommand extends MailMakeCommand
{
    use ComponentGenerator;

    protected $name = 'cmake:mail';

    protected string $relatedNamespace = '\Mail';
}
