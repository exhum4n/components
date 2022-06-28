<?php

declare(strict_types=1);

namespace Exhum4n\Components\Providers;

use Exhum4n\Components\Console\Commands\Install;
use Exhum4n\Components\Console\Commands\Migrate;
use Exhum4n\Components\Exceptions\Handler;
use Exhum4n\Components\Http\Middleware\Localization;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Database\Migrations\Migrator;

class ComponentsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->initializeMigrator();

        $this->registerInstallCommand();
        $this->registerMigrateCommand();
        $this->registerLocalizationMiddleware();
        $this->registerHelpers('path_helper.php');
        $this->publishConfig('components.php');

        $this->replaceExceptionHandler();
    }

    protected function initializeMigrator(): void
    {
        $this->app->singleton(Migrator::class, function ($app) {
            return $app['migrator'];
        });
    }

    protected function registerLocalizationMiddleware(): void
    {
        $router = $this->app['router'];

        $router->pushMiddlewareToGroup('web', Localization::class);
    }

    protected function registerMigrateCommand(): void
    {
        $this->commands([
            Migrate::class,
        ]);
    }

    protected function replaceExceptionHandler(): void
    {
        $this->app->bind(
            ExceptionHandler::class,
            Handler::class
        );
    }

    final function registerInstallCommand(): void
    {
        $name = 'exhum4n.components.install';

        $this->registerCommand($name, Install::class);

        $this->commands($name);
    }
}
