<?php

declare(strict_types=1);

namespace Exhum4n\Components\Providers;

use Exhum4n\Components\Console\ComponentsInstall;
use Exhum4n\Components\Http\Middleware\Localization;

class ComponentsServiceProvider extends AbstractProvider
{
    /**
     * {@inheritDoc}
     */
    public function register(): void
    {
        $this->registerInstallCommands();
        $this->registerLocalizationMiddleware();

        $this->registerHelpers('path_helper.php');
        $this->publishConfig('components.php');
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
     * Register installation command
     */
    private function registerInstallCommands(): void
    {
        $name = 'exhum4n.components.install';

        $this->registerCommand($name, ComponentsInstall::class);

        $this->commands('exhum4n.components.install');
    }
}
