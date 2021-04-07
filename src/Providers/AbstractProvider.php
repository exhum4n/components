<?php

declare(strict_types=1);

namespace Exhum4n\Components\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use ReflectionClass;

abstract class AbstractProvider extends ServiceProvider
{
    /**
     * Boot provider.
     */
    public function boot(): void
    {
        $componentRoot = $this->getComponentRoot();

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
     * @return string
     */
    protected function getComponentRoot(): string
    {
        $class_info = new ReflectionClass(static::class);

        return dirname($class_info->getFileName(), 2);
    }
}
