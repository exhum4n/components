<?php

namespace Exhum4n\Components\Console\Commands\Make;

use Exhum4n\Components\Console\Commands\Command;

abstract class Generator extends Command
{
    protected $name;

    public function handle(): void
    {
        $this->name = to_snake($this->input->getArgument('name'));
    }

    public function getName(): string
    {
        return ucwords($this->name);
    }

    /**
     * @return array<string>
     */
    protected function getComponentsNames(): array
    {
        $names = [];

        foreach (components_catalog()->toArray() as $name => $component) {
            $names[] = $name;
        }

        return $names;
    }

    protected function getComponentPath(string $component): string
    {
        return component_path(components_catalog()->getProvider($component));
    }
}
