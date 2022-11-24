<?php

declare(strict_types=1);

namespace Exhum4n\Components\Database\Seeds;

use Illuminate\Database\Seeder;

abstract class ComponentSeeder extends Seeder
{
    /**
     * @return array<Seeder>
     */
    abstract public function getSeeds(): array;

    public function run(): void
    {
        $this->call($this->getSeeds());
    }
}
