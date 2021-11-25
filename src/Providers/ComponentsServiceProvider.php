<?php

declare(strict_types=1);

namespace Exhum4n\Components\Providers;

use Exhum4n\Components\Console\ComponentsInstall;
use Exhum4n\Components\Exceptions\Handler;
use Exhum4n\Components\Http\Middleware\Localization;
use Illuminate\Contracts\Debug\ExceptionHandler;

class ComponentsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerInstallCommands();
        $this->registerCmakeCommands();
        $this->registerLocalizationMiddleware();

        $this->registerHelpers('path_helper.php');
        $this->publishConfig('components.php');

        $this->replaceExceptionHandler();
    }

    private function registerLocalizationMiddleware(): void
    {
        $router = $this->app['router'];

        $router->pushMiddlewareToGroup('api', Localization::class);
    }

    private function registerInstallCommands(): void
    {
        $name = 'exhum4n.components.install';

        $this->registerCommand($name, ComponentsInstall::class);

        $this->commands($name);
    }

    private function registerCmakeCommands(): void
    {
        $this->commands([
            \Exhum4n\Components\Console\Commands\CastCmakeCommand::class,
            \Exhum4n\Components\Console\Commands\ChannelCmakeCommand::class,
            \Exhum4n\Components\Console\Commands\ConsoleCmakeCommand::class,
            \Exhum4n\Components\Console\Commands\ControllerCmakeCommand::class,
            \Exhum4n\Components\Console\Commands\EntityCmakeCommand::class,
            \Exhum4n\Components\Console\Commands\EventCmakeCommand::class,
            \Exhum4n\Components\Console\Commands\ExceptionCmakeCommand::class,
            \Exhum4n\Components\Console\Commands\FactoryCmakeCommand::class,
            \Exhum4n\Components\Console\Commands\JobCmakeCommand::class,
            \Exhum4n\Components\Console\Commands\ListenerCmakeCommand::class,
            \Exhum4n\Components\Console\Commands\MailCmakeCommands::class,
            \Exhum4n\Components\Console\Commands\MiddlewareCmakeCommand::class,
            \Exhum4n\Components\Console\Commands\MigrationCmakeCommand::class,
            \Exhum4n\Components\Console\Commands\ModelCmakeCommand::class,
            \Exhum4n\Components\Console\Commands\NotificationCmakeCommand::class,
            \Exhum4n\Components\Console\Commands\ObserverCmakeCommand::class,
            \Exhum4n\Components\Console\Commands\PolicyCmakeClass::class,
            \Exhum4n\Components\Console\Commands\ProviderCmakeCommand::class,
            \Exhum4n\Components\Console\Commands\RepositoryCmakeCommand::class,
            \Exhum4n\Components\Console\Commands\RequestCmakeCommand::class,
            \Exhum4n\Components\Console\Commands\ResourceCmakeCommand::class,
            \Exhum4n\Components\Console\Commands\RuleCmakeCommand::class,
            \Exhum4n\Components\Console\Commands\SeederCmakeCommand::class,
            \Exhum4n\Components\Console\Commands\TestCmakeCommand::class
        ]);
    }

    private function replaceExceptionHandler(): void
    {
        $this->app->bind(
            ExceptionHandler::class,
            Handler::class
        );
    }
}
