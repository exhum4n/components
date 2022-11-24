<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;

if (function_exists('create_directory') === false) {
    function create_directory(string $path): string
    {
        if (File::isDirectory($path) === false) {
            File::makeDirectory($path, 0777, true, true);
        }

        return $path;
    }
}

if (function_exists('write_file') === false) {
    function write_file(string $path, string $filename, string $content): void
    {
        $relativePath = $path . DIRECTORY_SEPARATOR . $filename;

        if (File::exists($path) === false) {
            File::put($relativePath, $content);

            return;
        }

        File::replace($relativePath, $content);
    }
}
