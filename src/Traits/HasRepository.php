<?php

declare(strict_types=1);

namespace Exhum4n\Components\Traits;

use Exhum4n\Components\Repositories\EloquentRepository;

trait HasRepository
{
    abstract protected function getEloquentRepository(): string;
}
