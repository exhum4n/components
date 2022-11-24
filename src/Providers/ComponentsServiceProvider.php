<?php

declare(strict_types=1);

namespace Exhum4n\Components\Providers;

use Exhum4n\Components\Catalog;
use Exhum4n\Components\Console\Commands\Make\Component;
use Exhum4n\Components\Console\Commands\Make\Controller;
use Exhum4n\Components\Console\Commands\Make\Migration;
use Exhum4n\Components\Console\Commands\Make\Model;
use Exhum4n\Components\Console\Commands\Make\Repository;
use Exhum4n\Components\Console\Commands\Make\Service;
use Exhum4n\Components\Console\Commands\Migrate;
use Exhum4n\Components\Console\Commands\Wipe;
use Exhum4n\Components\Http\Middleware\Localization;
use Exhum4n\Components\Tools\Logger;
use Illuminate\Database\Migrations\Migrator;

class ComponentsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->init();

        $this->publishConfig('components.php');

        $this->registerCommands();
        $this->registerLocalizationMiddleware();
        $this->registerHelpers([
            'path_helper.php',
            'helper.php',
            'debug.php',
            'files.php',
            'string.php',
        ]);
    }

    protected function init(): void
    {
        $this->initializeMigrator();
        $this->initLogger();

        $this->app->singleton('components', Catalog::class);
    }

    protected function initLogger(): void
    {
        $this->app->bind('Log', function () {
            return new Logger();
        });
    }

    protected function initializeMigrator(): void
    {
        $this->app->singleton(Migrator::class, function ($app) {
            return $app['migrator'];
        });
    }

    protected function registerLocalizationMiddleware(): void
    {
        $this->app['router']->pushMiddlewareToGroup('web', Localization::class);
    }

    protected function registerCommands(): void
    {
        $this->commands([
            Migrate::class,
            Wipe::class,
            Component::class,
            Migration::class,
            Controller::class,
            Model::class,
            Repository::class,
            Service::class,
        ]);
    }

    protected function getComponentName(): string
    {
        return 'components';
    }
}
