<?php

namespace Exhum4n\Components\Console\Commands;

use Illuminate\Database\Console\Seeds\SeederMakeCommand;
use Illuminate\Support\Str;

class SeederCmakeCommand extends SeederMakeCommand
{
    use ComponentGenerator;

    protected $name = 'cmake:seeder';

    protected string $relatedNamespace = '\Database\Seeder';

    /**
     * @param  string  $name
     * @return string
     */
    protected function qualifyClass($name): string
    {
        $name = ltrim($name, '\\/');

        $name = str_replace('/', '\\', $name);

        $rootNamespace = $this->rootNamespace();

        if (Str::startsWith($name, $rootNamespace)) {
            return $name;
        }

        return $this->qualifyClass(
            $this->getDefaultNamespace(trim($rootNamespace, '\\')).'\\'.$name
        );
    }
}
