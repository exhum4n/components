<?php

declare(strict_types=1);

namespace Exhum4n\Components;

use Exhum4n\Components\Providers\ServiceProvider;
use Illuminate\Contracts\Support\Arrayable;

class Catalog implements Arrayable
{
    public const MAIN_COMPONENT_NAME = 'components';

    /**
     * @var array<ServiceProvider>
     */
    protected array $components = [];

    public function get(): array
    {
        return $this->components;
    }

    public function register(string $name, ServiceProvider $provider): void
    {
        if (isset($this->components[$name])) {
            return;
        }

        $this->components[$name] = $provider;
    }

    public function isExists(string $name): bool
    {
        return isset($this->components[$name]);
    }

    public function getProvider(string $name): ?ServiceProvider
    {
        if ($this->isExists($name) === false) {
            return null;
        }

        return $this->components[$name];
    }

    public function toArray(): array
    {
        return $this->components;
    }
}
