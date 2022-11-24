<?php

declare(strict_types=1);

namespace Exhum4n\Components\Console\Commands\Make;

class Migration extends ClassGenerator
{
    protected $name;
    protected string $component;

    protected function getActionName(): string
    {
        return 'migration {name : The name of the migration}';
    }

    protected function getStubName(): string
    {
        return 'migration.stub';
    }

    protected function getTargetFolder(): string
    {
        return 'Database' . DIRECTORY_SEPARATOR . 'Migrations';
    }

    /**
     * @return array<string, mixed>
     */
    protected function getStubVariables(): array
    {
        return [
            'class' => $this->getClassname(),
            'component' => $this->component,
            'table' => $this->getPluralName($this->name, false),
        ];
    }

    protected function getClassName(?bool $withPrefix = true): string
    {
        $schema = $this->getPluralName(to_camel($this->component));
        $table = $this->getPluralName(to_camel($this->name));

        return 'Create' . $schema . $table . 'Table';
    }

    protected function getFileName(string $classname): string
    {
        return now()->format('Y_m_d_u') . '_'. to_snake($classname) . '.php';
    }
}
