<?php

namespace Exhum4n\Components\Database\Seeds;

use Exhum4n\Components\Repositories\AbstractRepository;
use Illuminate\Database\Seeder;

abstract class AbstractSeeder extends Seeder
{
    abstract protected function getRecords(): array;

    abstract protected function getRepository(): string;

    /**
     * @var AbstractRepository
     */
    protected $repository;

    protected $records = [];

    public function __construct()
    {
        $repositoryClass = $this->getRepository();
        $this->records = $this->getRecords();

        $this->repository = new $repositoryClass();
    }

    public function run(): void
    {
        $seedClass = class_basename(static::class);

        $this->command->warn("Seeding: {$seedClass}");

        foreach ($this->records as $record) {
            $this->repository->firstOrCreate($record);
        }
    }
}
