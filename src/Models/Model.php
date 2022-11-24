<?php

/** @noinspection PhpUnused */

declare(strict_types=1);

namespace Exhum4n\Components\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as BaseModel;

/**
 * @property int id
 *
 * @method static Builder where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder create(array $attributes = [])
 * @method static Builder insert(array $attributes = [])
 * @method static Model firstOrCreate(array $attributes = [])
 * @method static LengthAwarePaginator paginate(?int $perPage = null)
 * @method Builder update(array $values)
 */
abstract class Model extends BaseModel
{
    public $timestamps = false;

    public function getFillableValues(): array
    {
        $values = [];

        foreach ($this->getFillable() as $key) {
            $value = $this->getAttribute($key);
            if ($value) {
                $values[$key] = $this->getAttribute($key);
            }
        }

        return $values;
    }
}
