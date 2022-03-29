<?php

declare(strict_types=1);

namespace Exhum4n\Components\Providers;

use Exhum4n\Components\Console\Commands\CastCmakeCommand;
use Exhum4n\Components\Console\Commands\ChannelCmakeCommand;
use Exhum4n\Components\Console\Commands\ConsoleCmakeCommand;
use Exhum4n\Components\Console\Commands\ControllerCmakeCommand;
use Exhum4n\Components\Console\Commands\EntityCmakeCommand;
use Exhum4n\Components\Console\Commands\EventCmakeCommand;
use Exhum4n\Components\Console\Commands\ExceptionCmakeCommand;
use Exhum4n\Components\Console\Commands\FactoryCmakeCommand;
use Exhum4n\Components\Console\Commands\JobCmakeCommand;
use Exhum4n\Components\Console\Commands\ListenerCmakeCommand;
use Exhum4n\Components\Console\Commands\MailCmakeCommands;
use Exhum4n\Components\Console\Commands\MiddlewareCmakeCommand;
use Exhum4n\Components\Console\Commands\MigrateCommand;
use Exhum4n\Components\Console\Commands\MigrationCmakeCommand;
use Exhum4n\Components\Console\Commands\ModelCmakeCommand;
use Exhum4n\Components\Console\Commands\NotificationCmakeCommand;
use Exhum4n\Components\Console\Commands\ObserverCmakeCommand;
use Exhum4n\Components\Console\Commands\PolicyCmakeClass;
use Exhum4n\Components\Console\Commands\ProviderCmakeCommand;
use Exhum4n\Components\Console\Commands\RepositoryCmakeCommand;
use Exhum4n\Components\Console\Commands\RequestCmakeCommand;
use Exhum4n\Components\Console\Commands\ResourceCmakeCommand;
use Exhum4n\Components\Console\Commands\RuleCmakeCommand;
use Exhum4n\Components\Console\Commands\SeederCmakeCommand;
use Exhum4n\Components\Console\Commands\TestCmakeCommand;
use Exhum4n\Components\Console\ComponentsInstall;
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
        $this->registerCmakeCommands();
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

    protected function registerCmakeCommands(): void
    {
        $this->commands([
            CastCmakeCommand::class,
            ChannelCmakeCommand::class,
            ConsoleCmakeCommand::class,
            ControllerCmakeCommand::class,
            EntityCmakeCommand::class,
            EventCmakeCommand::class,
            ExceptionCmakeCommand::class,
            FactoryCmakeCommand::class,
            JobCmakeCommand::class,
            ListenerCmakeCommand::class,
            MailCmakeCommands::class,
            MiddlewareCmakeCommand::class,
            MigrationCmakeCommand::class,
            ModelCmakeCommand::class,
            NotificationCmakeCommand::class,
            ObserverCmakeCommand::class,
            PolicyCmakeClass::class,
            ProviderCmakeCommand::class,
            RepositoryCmakeCommand::class,
            RequestCmakeCommand::class,
            ResourceCmakeCommand::class,
            RuleCmakeCommand::class,
            SeederCmakeCommand::class,
            TestCmakeCommand::class
        ]);
    }

    protected function registerMigrateCommand(): void
    {
        $this->commands([
            MigrateCommand::class,
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

        $this->registerCommand($name, ComponentsInstall::class);

        $this->commands($name);
    }
}
