<?php

declare(strict_types=1);

namespace Exhum4n\Components\Traits;

use Exhum4n\Components\Services\Service;

trait HasService
{
    protected Service $service;

    abstract protected function getService(): string;
}