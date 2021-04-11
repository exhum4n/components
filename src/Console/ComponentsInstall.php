<?php

declare(strict_types=1);

namespace Exhum4n\Components\Console;

use Exhum4n\Components\Providers\ComponentsServiceProvider;

class ComponentsInstall extends Installer
{
    /**
     * {@inheritDoc}
     */
    public function handle(): void
    {
        parent::handle();

        $this->call('vendor:publish', [
            '--provider' => ComponentsServiceProvider::class
        ]);
    }

    /**
     * @return string
     */
    protected function getSignature(): string
    {
        return 'components:install';
    }
}
