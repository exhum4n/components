<?php

/** @noinspection PhpIncludeInspection */

declare(strict_types=1);

namespace Exhum4n\Components\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use ReflectionClass;

abstract class AbstractProvider extends ServiceProvider
{
    /**
     * @var string
     */
    protected $configPath = '../config/config.php';

    /**
     * @var string
     */
    protected $viewsPath = '../resources/views';

    /**
     * Boot provider.
     */
    public function boot(): void
    {
        $componentRoot = $this->getPackageRoot();

        $this->loadMigrationsFrom("{$componentRoot}/Database/Migrations/");

        $apiRoutesPath = "{$componentRoot}/Routes/api.php";
        if (file_exists($apiRoutesPath)) {
            Route::prefix('api')
                ->middleware('api')
                ->group("{$componentRoot}/Routes/api.php");
        }

        $webRoutesPath = "{$componentRoot}/Routes/web.php";
        if (file_exists($webRoutesPath)) {
            Route::middleware('web')
                ->group("{$componentRoot}/Routes/web.php");
        }
    }

    /**
     * Register console command.
     *
     * @param string $signature
     * @param string $className
     */
    protected function registerCommand(string $signature, string $className): void
    {
        $this->app->singleton($signature, function () use ($className) {
            return new $className();
        });

        $this->commands($signature);
    }


    /**
     * Register views to global namespace
     *
     * @param string $dirName
     * @param string $namespace
     */
    protected function registerViews(string $dirName, string $namespace): void
    {
        $componentRoot = $this->getPackageRoot();

        $this->publishes([
            "{$componentRoot}/{$this->viewsPath}/{$dirName}" => base_path("resources/views/{$namespace}")
        ], 'views');
    }

    /**
     * Register helpers.
     *
     * @param string $fileName
     */
    protected function registerHelpers(string $fileName)
    {
        $componentRoot = $this->getPackageRoot();

        if ($file = "{$componentRoot}/Helpers/{$fileName}") {
            require $file;
        }
    }

    /**
     * Add ability to publish config
     *
     * @param string $name
     */
    protected function publishConfig(string $name)
    {
        $componentRoot = $this->getPackageRoot();

        $this->publishes([
            "{$componentRoot}/{$this->configPath}" => config_path($name)
        ], 'config');
    }

    /**
     * @param string $abstract
     * @param string $concrete
     */
    protected function bind(string $abstract, string $concrete): void
    {
        $this->app->bind($abstract, $concrete);
    }

    /**
     * Merge package config with app config
     *
     * @param string $name
     */
    protected function mergeConfigs(string $name)
    {
        $componentRoot = $this->getPackageRoot();

        $this->mergeConfigFrom("{$componentRoot}/{$this->configPath}", $name);
    }

    /**
     * Return package root path
     *
     * @return string
     */
    private function getPackageRoot(): string
    {
        $class_info = new ReflectionClass(static::class);

        return dirname($class_info->getFileName(), 2);
    }
}
