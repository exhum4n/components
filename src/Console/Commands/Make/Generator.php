<?php

namespace Exhum4n\Components\Console\Commands\Make;

use Exhum4n\Components\Console\Commands\Command;

abstract class Generator extends Command
{
    protected $name;

    private array $makeOptions = [
        'component' => 'The component name',
    ];

    public function __construct()
    {
        $this->options = array_merge($this->makeOptions, $this->options);

        parent::__construct();
    }

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

        foreach (components_catalog()->get() as $name => $provider) {
            if ($name === $this->getComponentName()) {
                continue;
            }

            $names[] = $name;
        }

        return $names;
    }

    protected function getComponentPath(string $component): string
    {
        $catalog = components_catalog();
        if ($catalog->isExists($component)) {
            return component_path(components_catalog()->getProvider($component));
        }

        return 'components' . DIRECTORY_SEPARATOR . $component;
    }
}
