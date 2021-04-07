<?php

/** @noinspection PhpIncludeInspection */

declare(strict_types=1);

namespace Exhum4n\Components\Providers;

use Exhum4n\Components\Console\ComponentsInstall;
use Exhum4n\Components\Database\Migrator;
use Exhum4n\Components\Http\Middleware\Localization;
use Illuminate\Database\Migrations\Migrator as BaseMigrator;

class ComponentsServiceProvider extends AbstractProvider
{
    /**
     * {@inheritDoc}
     */
    public function boot(): void
    {
        parent::boot();

        $path = realpath(__DIR__ . '/../../config/config.php');

        $this->publishes([$path => config_path('components.php')], 'config');
        $this->mergeConfigFrom($path, 'components');
    }

    /**
     * {@inheritDoc}
     */
    public function register(): void
    {
        $this->registerHelpers();
        $this->registerInstallCommands();
        $this->registerLocalizationMiddleware();
        $this->disableRollbackErrors();

        $this->commands('exhum4n.components.install');
    }

    /**
     * Register localization middleware.
     */
    private function registerLocalizationMiddleware(): void
    {
        $router = $this->app['router'];

        $router->pushMiddlewareToGroup('api', Localization::class);
    }

    /**
     * Disable migration warnings messages.
     */
    private function disableRollbackErrors(): void
    {
        $this->app->singleton('migrator', function ($app) {
            $repository = $app['migration.repository'];

            return new Migrator($repository, $app['db'], $app['files'], $app['events']);
        });

        $this->app->bind(BaseMigrator::class, Migrator::class);
    }

    /**
     * Register helpers
     */
    private function registerHelpers(): void
    {
        if (file_exists($file = dirname(__DIR__) . '/Helpers/path_helper.php')) {
            require $file;
        }
    }

    /**
     * Register installation command
     */
    private function registerInstallCommands(): void
    {
        $this->app->singleton('exhum4n.components.install', function () {
            return new ComponentsInstall();
        });
    }
}
