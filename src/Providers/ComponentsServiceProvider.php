<?php

declare(strict_types=1);

namespace Exhum4n\Components\Providers;

use Exhum4n\Components\Console\Commands\Install;
use Exhum4n\Components\Console\Commands\Migrate;
use Exhum4n\Components\Console\Commands\Refresh;
use Exhum4n\Components\Exceptions\Handler;
use Exhum4n\Components\Http\Middleware\Localization;
use Exhum4n\Components\Tools\Logger;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Database\Migrations\Migrator;

class ComponentsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->initializeMigrator();

        $this->initLogger();
        $this->registerCommands();
        $this->registerLocalizationMiddleware();
        $this->registerHelpers('path_helper.php');
        $this->publishConfig('components.php');

        $this->replaceExceptionHandler();
    }

    protected function initLogger(): void
    {
        $this->app->singleton('Logger', function ($app) {
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
        $router = $this->app['router'];

        $router->pushMiddlewareToGroup('web', Localization::class);
    }

    protected function registerCommands(): void
    {
        $this->commands([
            Migrate::class,
            Install::class,
//            Refresh::class,
        ]);
    }

    final function replaceExceptionHandler(): void
    {
        $this->app->bind(
            ExceptionHandler::class,
            Handler::class
        );
    }
}
