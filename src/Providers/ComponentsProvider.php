<?php

declare(strict_types=1);

namespace Exhum4n\Components\Providers;

class ComponentsProvider extends AbstractProvider
{
    public function register()
    {
        $this->registerHelpers();
    }

    private function registerHelpers()
    {
        if (file_exists($file = dirname(__DIR__) . '/Helpers/path_helper.php')) {
            require dirname(__DIR__) . '/Helpers/path_helper.php';
        }
    }
}
