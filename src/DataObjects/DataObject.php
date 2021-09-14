<?php

declare(strict_types=1);

namespace Exhum4n\Components\DataObjects;

abstract class DataObject
{
    public function __construct(array $values = [])
    {
        foreach ($values as $key => $value) {
            $key = lcfirst($key);

            if (property_exists(static::class, $key) && $value !== null) {
                $this->$key = $value;
            }
        }
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
