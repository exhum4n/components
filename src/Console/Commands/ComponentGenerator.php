<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace Exhum4n\Components\Console\Commands;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

trait ComponentGenerator
{
    protected function rootNamespace(): string
    {
        if (empty($this->option('component'))) {
            return parent::rootNamespace();
        }

        return 'Components\\';
    }

    /**
     * @param string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        if (empty($this->option('component'))) {
            return parent::getDefaultNamespace($rootNamespace);
        }

        return $rootNamespace . '\\' . $this->input->getOption('component') . $this->relatedNamespace;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function getPath($name): string
    {
        if (empty($this->option('component'))) {
            return parent::getPath($name);
        }

        $name = Str::replaceFirst($this->rootNamespace(), '', $name);
        return 'components/' . str_replace('\\', '/', $name) . '.php';
    }

    /**
     * @param string $stub
     *
     * @return string
     */
    protected function resolveStubPath($stub): string
    {
        if (empty($this->option('component'))) {
            return parent::resolveStubPath($stub);
        }

        $stub = preg_replace('~(^.*/)~', '$1component-', $stub);

        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__ . $stub;
    }

    protected function getOptions(): array
    {
        $options = $this->markBrokenOptions(parent::getOptions());

        return array_merge($options, [['component', 'C', InputOption::VALUE_REQUIRED, 'Choose a component']]);
    }

    protected function markBrokenOptions(array $options): array
    {
        if (isset($this->brokenOptions) === false) {
            return $options;
        }

        foreach ($options as $key => $option) {
            if (in_array($option[0], $this->brokenOptions)) {
                $options[$key][3] = '[Don`t work with --component option] ' . $option[3];
            }
        }

        return $options;
    }
}
