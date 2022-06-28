<?php

declare(strict_types=1);

namespace Exhum4n\Components\Console\Commands;

use Exhum4n\Components\Console\Installer;
use Exhum4n\Components\Providers\ComponentsServiceProvider;

class Install extends Installer
{
    public function handle(): void
    {
        parent::handle();

        $this->call('vendor:publish', [
            '--provider' => ComponentsServiceProvider::class
        ]);
    }

    protected function getSignature(): string
    {
        return 'components:install';
    }
}
