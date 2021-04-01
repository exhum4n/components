<?php

declare(strict_types=1);

use Prophecy\Exception\Doubler\ClassNotFoundException;

if (function_exists('migrations_path') === false) {
    function migrations_path(string $className): string
    {
        try {
            $class_info = new ReflectionClass($className);
        } catch (ReflectionException $e) {
            throw new ClassNotFoundException('Cannot find class', $className);
        }

        $componentRoot = dirname($class_info->getFileName(), 2);

        $componentRoot = str_replace('/var/www', '', $componentRoot);

        return "{$componentRoot}/Database/Migrations";
    }
}
