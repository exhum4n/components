<?php

namespace Exhum4n\Components\Repositories;

class RequestCacheRepository extends RedisRepository
{
    protected string $prefix = 'request_cache';
}
