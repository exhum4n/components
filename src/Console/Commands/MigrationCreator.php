<?php

namespace Exhum4n\Components\Console\Commands;

use Illuminate\Database\Migrations\MigrationCreator as BaseMigrationCreator;

class MigrationCreator extends BaseMigrationCreator
{
    public function stubPath(): string
    {
        return __DIR__.'/stubs';
    }

    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function getStub($table, $create): string
    {
        if (is_null($table)) {
            $stub = $this->files->exists($customPath = $this->customStubPath . '/component-migration.stub')
                ? $customPath
                : $this->stubPath() . '/component-migration.stub';
        } elseif ($create) {
            $stub = $this->files->exists($customPath = $this->customStubPath . '/component-migration.create.stub')
                ? $customPath
                : $this->stubPath() . '/component-migration.create.stub';
        } else {
            $stub = $this->files->exists($customPath = $this->customStubPath . '/component-migration.update.stub')
                ? $customPath
                : $this->stubPath() . '/component-migration.update.stub';
        }

        return $this->files->get($stub);
    }
}
