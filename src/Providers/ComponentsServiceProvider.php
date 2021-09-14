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

    private function replaceExceptionHandler(): void
    {
        $this->app->bind(
            ExceptionHandler::class,
            Handler::class
        );
    }
}
