<?php

/**
 * @noinspection PhpUndefinedMethodInspection
 */

declare(strict_types=1);

namespace Exhum4n\Components\Services;

use Exhum4n\Components\Repositories\EloquentRepository;
use Exhum4n\Components\Repositories\RedisRepository;
use Exhum4n\Components\Traits\HasRepository;
use Exhum4n\Components\Traits\Loggable;
use Exhum4n\Components\Traits\UsesRedis;

abstract class Service
{
    public EloquentRepository $repository;

    protected RedisRepository $cache;

    public function __construct()
    {
        $traits = class_uses_recursive(static::class);

        if (in_array(HasRepository::class, $traits)) {
            $this->initializeRepository();
        }

        if (in_array(UsesRedis::class, $traits)) {
            $this->initializeRedis();
        }
    }

    private function initializeRepository(): void
    {
        $this->repository = app(static::getEloquentRepository());
    }

    private function initializeRedis(): void
    {
        $this->cache = app(static::getRedisRepository());
    }
}
