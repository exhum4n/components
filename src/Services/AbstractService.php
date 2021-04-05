<?php

declare(strict_types=1);

namespace Exhum4n\Components\Services;

use Exception;
use Exhum4n\Components\Repositories\AbstractRepository;
use Exhum4n\Components\Repositories\RedisRepository;
use Exhum4n\Components\Traits\Loggable;

abstract class AbstractService
{
    use Loggable;

    /**
     * @var AbstractRepository|RedisRepository
     */
    protected $repository;

    public function __construct()
    {
        $repositoryClass = $this->getRepository();

        $this->repository = app($repositoryClass);

        $logName = $this->getLogName();

        $this->initLogger($logName);
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function __call($name, $arguments)
    {
        if ($this->isMethodExist($name) === false) {
            if ($this->isMethodExistInRepository($name)) {
                return $this->repository->{$name}(...$arguments);
            }

            throw new Exception('Method not found in ', $this->repository, $name);
        }

        return static::$name(...$arguments);
    }

    protected function getLogName(): string
    {
        $childServiceClass = static::class;

        $serviceName = class_basename($childServiceClass);

        $moduleName = str_replace('Service', '', $serviceName);

        return strtolower($moduleName);
    }

    protected function isMethodExist(string $name): bool
    {
        return method_exists(static::class, $name);
    }

    protected function isMethodExistInRepository(string $name): bool
    {
        return method_exists($this->repository, $name);
    }

    abstract protected function getRepository(): string;
}
