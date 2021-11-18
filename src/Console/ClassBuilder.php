<?php

declare(strict_types=1);


namespace Exhum4n\Components\Console;


class ClassBuilder
{
    protected string $stub;

    public function __construct(string $stub)
    {
        $this->stub = $stub;
    }

    public function getClassContent(array $replaces): string
    {
        $this->replace($replaces);
        $this->sortImportedNamespaces($this->stub);

        return $this->stub;
    }

    protected function replace($replaces)
    {
        foreach ($replaces as $placeholder => $replaceData) {
            $this->stub = str_replace($placeholder, $replaceData, $this->stub);
        }
    }

    protected function sortImportedNamespaces(string $stub): void
    {
        $stubHasNamespaces = preg_match('/(?P<imports>(?:use [^;]+;$\n?)+)/m', $stub, $match);

        if ($stubHasNamespaces) {
            $defaultNamespaceBlock = trim($match['imports']);

            $namespaces = explode("\n", $defaultNamespaceBlock);

            sort($namespaces);

            $newSortedNamespaceBlock = implode("\n", $namespaces);

            $this->stub =  str_replace($defaultNamespaceBlock, $newSortedNamespaceBlock, $stub);
        }
    }
}
