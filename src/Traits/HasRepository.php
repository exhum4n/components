<?php

declare(strict_types=1);

namespace Exhum4n\Components\Traits;

trait HasRepository
{
    abstract protected function getEloquentRepository(): string;
}