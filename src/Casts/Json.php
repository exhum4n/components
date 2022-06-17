<?php

declare(strict_types=1);

namespace Exhum4n\Components\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class Json implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): array
    {
        if (is_null($value)) {
            return [];
        }

        return json_decode($value, true);
    }

    public function set($model, string $key, $value, array $attributes): string
    {
        if (is_null($value)) {
            return '';
        }

        return json_encode($value);
    }
}
