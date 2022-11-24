<?php

declare(strict_types=1);

use Exhum4n\Components\Providers\ServiceProvider;

if (function_exists('migrations_path') === false) {
    function migrations_path(ServiceProvider $provider): string
    {
        return component_path($provider) . '/Database/Migrations';
    }
}

if (function_exists('component_path') === false) {
    function component_path(ServiceProvider $provider): string
    {
        $class_info = new ReflectionClass($provider);

        $componentPath = dirname($class_info->getFileName(), 2);

        $relativePath = str_replace(base_path(), '', $componentPath);
        if ($relativePath[0] === DIRECTORY_SEPARATOR) {
            $relativePath = substr($relativePath, 1, strlen($relativePath));
        }

        return $relativePath;
    }
}

if (function_exists('correct_directory_separators') === false) {
    function correct_directory_separators(string $path): string
    {
        return str_replace('\\', '/', $path);
    }
}
