<?php

namespace Exhum4n\Components\Console\Commands;

use Illuminate\Database\Console\Migrations\MigrateMakeCommand;

class MigrateCmakeCommand extends MigrateMakeCommand
{
    protected $signature = 'cmake:migration {name : The name of the migration}
        {--create= : The table to be created}
        {--table= : The table to migrate}
        {--path= : The location where the migration file should be created}
        {--realpath : Indicate any provided migration file paths are pre-resolved absolute paths}
        {--fullpath : Output the full path of the migration}
        {--C|component= : Choose a component}';

    public function handle(): void
    {
        if ((null !== $component = $this->input->getOption('component'))
            && null === $this->input->getOption('path')
        ) {
            $this->input->setOption('path', "components/$component/Database/Migrations");
        }

        parent::handle();
    }
}
