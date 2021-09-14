<?php

/** @noinspection PhpIncludeInspection */

declare(strict_types=1);

namespace Exhum4n\Components\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use ReflectionClass;

abstract class ServiceProvider extends BaseServiceProvider
{
    protected string $configPath = '../config/config.php';

    protected string $viewsPath = '../resources/views';

    public function boot(): void
    {
        $componentRoot = $this->getPackageRoot();

        $this->loadMigrationsFrom("$componentRoot/Database/Migrations/");

        $apiRoutesPath = "$componentRoot/Routes/api.php";
        if (file_exists($apiRoutesPath)) {
            Route::prefix('api')
                ->middleware('api')
                ->group("$componentRoot/Routes/api.php");
        }

        $webRoutesPath = "$componentRoot/Routes/web.php";
        if (file_exists($webRoutesPath)) {
            Route::middleware('web')
                ->group("$componentRoot/Routes/web.php");
        }
    }

    protected function registerCommand(string $name, string $className): void
    {
        $this->app->singleton($name, function () use ($className) {
            return new $className();
        });

        $this->commands($name);
    }

    protected function registerViews(string $dirName, string $namespace): void
    {
        $componentRoot = $this->getPackageRoot();

        $this->publishes([
            "$componentRoot/$this->viewsPath/$dirName" => base_path("resources/views/$namespace")
        ], 'views');
    }

    protected function registerHelpers(string $fileName)
    {
        $componentRoot = $this->getPackageRoot();

        if ($file = "$componentRoot/Helpers/$fileName") {
            require $file;
        }
    }

    protected function publishConfig(string $name)
    {
        $componentRoot = $this->getPackageRoot();

        $this->publishes([
            "$componentRoot/$this->configPath" => config_path($name)
        ], 'config');
    }

    protected function bind(string $abstract, string $concrete): void
    {
        $this->app->bind($abstract, $concrete);
    }

    protected function mergeConfigs(string $name)
    {
        $componentRoot = $this->getPackageRoot();

        $this->mergeConfigFrom("$componentRoot/$this->configPath", $name);
    }

    private function getPackageRoot(): string
    {
        $class_info = new ReflectionClass(static::class);

        return dirname($class_info->getFileName(), 2);
    }
}
