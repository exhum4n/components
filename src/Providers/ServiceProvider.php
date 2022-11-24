<?php

/** @noinspection PhpUnused */

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
        components_catalog()->register($this->getComponentName(), $this);

        $componentRoot = base_path($this->getPackageRoot());

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

    public function getPackageRoot(): string
    {
        $class_info = new ReflectionClass(static::class);

        $componentPath = dirname($class_info->getFileName(), 2);

        return str_replace(array(base_path(), '\\'), array('', '/'), $componentPath);
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

    protected function registerHelpers(array $helpers): void
    {
        foreach ($helpers as $helper) {
            $this->registerHelper($helper);
        }
    }

    protected function registerHelper(string $fileName): void
    {
        $componentRoot = base_path($this->getPackageRoot());

        if ($file = "$componentRoot/Helpers/$fileName") {
            require $file;
        }
    }

    protected function publishConfig(string $name): void
    {
        $componentRoot = base_path($this->getPackageRoot());

        $this->publishes([
            "$componentRoot/$this->configPath" => config_path($name)
        ], 'config');
    }

    protected function mergeConfigs(string $name): void
    {
        $componentRoot = $this->getPackageRoot();

        $this->mergeConfigFrom("$componentRoot/$this->configPath", $name);
    }

    abstract protected function getComponentName(): string;
}
