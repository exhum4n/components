<?php

declare(strict_types=1);

namespace Exhum4n\Components\Providers;

use Illuminate\Support\ServiceProvider;
use ReflectionClass;

abstract class AbstractProvider extends ServiceProvider
{
    public function boot(): void
    {
        $componentRoot = $this->getComponentRoot();

        $this->loadMigrationsFrom("{$componentRoot}/Database/Migrations/");

        $apiRoutesPath = "{$componentRoot}/Routes/api.php";
        if (file_exists($apiRoutesPath)) {
            $this->loadRoutesFrom("{$componentRoot}/Routes/api.php");
        }

        $webRoutesPath = "{$componentRoot}/Routes/web.php";
        if (file_exists($webRoutesPath)) {
            $this->loadRoutesFrom("{$componentRoot}/Routes/web.php");
        }
    }

    protected function getComponentRoot(): string
    {
        $class_info = new ReflectionClass(static::class);

        return dirname($class_info->getFileName(), 2);
    }
}
