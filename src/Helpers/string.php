<?php

declare(strict_types=1);

use Illuminate\Support\Str;

if (function_exists('to_snake') === false) {
    function to_snake(string $camelString): string
    {
        return Str::snake(trim($camelString));
    }
}

if (function_exists('to_camel') === false) {
    function to_camel(string $camelString): string
    {
        return Str::camel(trim($camelString));
    }
}
