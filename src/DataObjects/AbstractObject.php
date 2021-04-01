<?php

declare(strict_types=1);

namespace Exhum4n\Components\DataObjects;

abstract class AbstractObject
{
    /**
     * @param array $values
     */
    public function __construct($values = [])
    {
        foreach ($values as $key => $value) {
            $key = lcfirst($key);

            if (property_exists(static::class, $key) && $value !== null) {
                $this->$key = $value;
            }
        }
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
