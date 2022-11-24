<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace Exhum4n\Components\Models\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class Json implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): array
    {
        if (is_null($value)) {
            return [];
        }

        return json_decode($value, true, 512, JSON_THROW_ON_ERROR);
    }

    public function set($model, string $key, $value, array $attributes): string
    {
        if (is_null($value)) {
            return '';
        }

        return json_encode($value, JSON_THROW_ON_ERROR);
    }
}
