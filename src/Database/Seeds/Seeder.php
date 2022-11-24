<?php

declare(strict_types=1);

namespace Exhum4n\Components\Database\Seeds;

use Exhum4n\Components\Repositories\EloquentRepository;
use Illuminate\Database\Seeder as BaseSeeder;

abstract class Seeder extends BaseSeeder
{
    protected EloquentRepository $repository;

    protected array $records = [];

    public function __construct()
    {
        $repositoryClass = $this->getRepository();

        $this->repository = new $repositoryClass();

        $this->records = $this->getRecords();
    }

    public function run(): void
    {
        $seedClass = class_basename(static::class);

        $this->command->warn("Seeding: $seedClass");

        foreach ($this->records as $record) {
            $this->seed($record);
        }
    }

    protected function seed(array $record): void
    {
        $this->repository->firstOrCreate($record);
    }

    abstract protected function getRecords(): array;

    abstract protected function getRepository(): string;
}
