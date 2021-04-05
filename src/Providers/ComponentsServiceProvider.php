<?php

declare(strict_types=1);

namespace Exhum4n\Components\Providers;

use Exhum4n\Components\Console\ComponentsInstall;

class ComponentsServiceProvider extends AbstractProvider
{
    public function boot(): void
    {
        parent::boot();

        $path = realpath(__DIR__ . '/../../config/config.php');

        $this->publishes([$path => config_path('components.php')], 'config');
        $this->mergeConfigFrom($path, 'components');
    }

    public function register()
    {
        $this->registerHelpers();
        $this->registerInstallCommands();

        $this->commands('exhum4n.components.install');
    }

    private function registerHelpers()
    {
        if (file_exists($file = dirname(__DIR__) . '/Helpers/path_helper.php')) {
            require $file;
        }
    }

    private function registerInstallCommands(): void
    {
        $this->app->singleton('exhum4n.components.install', function () {
            return new ComponentsInstall();
        });
    }
}
