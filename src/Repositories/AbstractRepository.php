<?php

declare(strict_types=1);

namespace Exhum4n\Components\Repositories;

use Exhum4n\Components\Models\AbstractModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

abstract class AbstractRepository
{
    /**
     * @var AbstractModel
     */
    protected $model;

    /**
     * EloquentRepository constructor.
     */
    public function __construct()
    {
        $this->model = $this->getModel();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return class_basename(static::class);
    }

    /**
     * @return Builder
     */
    public function getQuery(): Builder
    {
        return $this->model::query();
    }

    /**
     * @param array $where
     *
     * @return Builder|Model|object|null
     */
    public function getFirst(array $where)
    {
        return $this->model::where($where)->first();
    }

    /**
     * @param int $id
     *
     * @return Builder|Model|object|null
     */
    public function getById(int $id)
    {
        return $this->getFirst(['id' => $id]);
    }

    /**
     * @param array $where
     *
     * @return Collection|null
     */
    public function get(array $where): ?Collection
    {
        return $this->model::where($where)->get();
    }

    /**
     * @return Collection|null
     */
    public function getAll(): ?Collection
    {
        return $this->model::all();
    }

    /**
     * @param int|null $perPage
     * @param array|null $filters
     *
     * @return LengthAwarePaginator
     */
    public function getWithPagination(?int $perPage = null, ?array $filters = null): LengthAwarePaginator
    {
        return $this->model::where($filters)->paginate($perPage);
    }

    /**
     * @param array $data
     *
     * @return Model
     */
    public function create(array $data): Model
    {
        $newRecord = new $this->model();

        $newRecord->fill($data);
        $newRecord->save();

        return $newRecord;
    }

    /**
     * @param Model $model
     * @param array $data
     *
     * @return Model
     */
    public function update(Model $model, array $data): Model
    {
        if (get_class($model) !== $this->model) {
            throw new ModelNotFoundException('Wrong model class');
        }

        $model->fill($data);

        $model->save();

        return $model;
    }

    /**
     * @param int $id
     * @param array $attributes
     *
     * @return Model|Builder
     */
    public function updateOrCreateById(int $id, array $attributes = []): Model
    {
        $record = $this->getFirst(['id' => $id]);
        if (is_null($record)) {
            return $this->create($attributes);
        }

        return $this->update($record, $attributes);
    }

    public function delete(int $id): int
    {
        return $this->model::destroy($id);
    }

    public function exist(array $where): bool
    {
        return $this->model::where($where)->exists();
    }

    /**
     * @param array $where
     *
     * @return Model
     */
    public function firstOrCreate(array $where): Model
    {
        return $this->model::firstOrCreate($where);
    }

    /**
     * @param callable $transaction
     *
     * @return mixed
     */
    public function transactionWrapper(callable $transaction)
    {
        $this->beginTransaction();

        try {
            $result = $transaction();

            $this->commit();

            return $result;
        } catch (QueryException $exception) {
            $this->rollback();

            throw $exception;
        }
    }

    public function beginTransaction(): void
    {
        DB::beginTransaction();
    }

    public function rollback(): void
    {
        DB::rollBack();
    }

    public function commit(): void
    {
        DB::commit();
    }

    abstract protected function getModel(): string;
}
