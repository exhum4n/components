<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

use Exhum4n\Components\Catalog;

if (function_exists('components_catalog') === false) {
    function components_catalog(): Catalog
    {
        return app()->get('components');
    }
}
