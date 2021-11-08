<?php

namespace Exhum4n\Components\Providers;

use Exhum4n\Components\Console\Commands\MigrateCmakeCommand;
use Exhum4n\Components\Console\Commands\MigrationCreator;
use Illuminate\Database\MigrationServiceProvider as BaseMigrationServiceProvider;

class MigrationServiceProvider extends BaseMigrationServiceProvider
{
    protected function registerCreator()
    {
        $this->app->singleton('migration.creator', function ($app) {
            return new MigrationCreator($app['files'], $app->basePath('stubs'));
        });
    }

    protected function registerMigrateMakeCommand()
    {
        $this->app->singleton('command.migrate.make', function ($app) {
            // Once we have the migration creator registered, we will create the command
            // and inject the creator. The creator is responsible for the actual file
            // creation of the migrations, and may be extended by these developers.
            $creator = $app['migration.creator'];

            $composer = $app['composer'];

            return new MigrateCmakeCommand($creator, $composer);
        });
    }
}
