<?php

declare(strict_types=1);

namespace Exhum4n\Components\Repositories;

use Exhum4n\Components\Models\Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

abstract class EloquentRepository
{
    protected string $model;

    public function __construct()
    {
        $this->model = $this->getModel();
    }

    public function __toString(): string
    {
        return class_basename(static::class);
    }

    public function getQuery(): Builder
    {
        return $this->model::query();
    }

    public function getFirst(array $where): ?Model
    {
        return $this->model::where($where)->first();
    }

    public function getById(int $id): ?Model
    {
        return $this->getFirst([
            'id' => $id
        ]);
    }

    public function getByName(string $name): ?Model
    {
        return $this->getFirst([
            'name' => $name
        ]);
    }

    public function get(array $where): ?Collection
    {
        return $this->model::where($where)->get();
    }

    public function getAll(): ?Collection
    {
        return $this->model::all();
    }

    public function getWithPagination(?array $filters = null): LengthAwarePaginator
    {
        return $this->model::where($filters)
            ->paginate();
    }

    public function create(array $data): ?Model
    {
        $newRecord = app($this->model);

        $newRecord->fill($data);
        $newRecord->save();

        return $newRecord;
    }

    public function update(Model $model, array $data): ?Model
    {
        if (get_class($model) !== $this->model) {
            throw new ModelNotFoundException('Wrong model class');
        }

        $model->fill($data);

        $model->save();

        return $model;
    }

    public function delete(int $id): int
    {
        return $this->model::destroy($id);
    }

    public function exist(array $where): bool
    {
        return $this->model::where($where)->exists();
    }

    public function firstOrCreate(array $where): Model
    {
        return $this->model::firstOrCreate($where);
    }

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
