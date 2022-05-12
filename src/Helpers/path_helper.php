<?php

declare(strict_types=1);

use Symfony\Component\ErrorHandler\Error\ClassNotFoundError;

if (function_exists('migrations_path') === false) {
    function migrations_path(string $className): string
    {
        try {
            $class_info = new ReflectionClass($className);
        } catch (ReflectionException) {
            throw new ClassNotFoundError('Cannot find class', $className);
        }

        $componentRoot = dirname($class_info->getFileName(), 2);

        $componentRoot = str_replace(base_path(), '', $componentRoot);

        return $componentRoot . DIRECTORY_SEPARATOR . 'Database' . DIRECTORY_SEPARATOR . 'Migrations';
    }
}
