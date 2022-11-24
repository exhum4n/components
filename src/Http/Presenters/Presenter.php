<?php

declare(strict_types=1);

namespace Exhum4n\Components\Http\Presenters;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

abstract class Presenter
{
    public function present(int $code = 200): JsonResponse|LengthAwarePaginator|Response
    {
        $presentationData = $this->getPresentationData();
        if (is_null($presentationData)) {
            return response(status: $code);
        }

        return response()->json($this->getPresentationData(), $code);
    }

    public function paginate(Collection $collection): array
    {
        $paginator = $this->createPaginator($collection);

        return [
            'total' => $paginator->total(),
            'currentPage' => $paginator->currentPage(),
            'lastPage' => $paginator->lastPage(),
            'perPage' => $paginator->perPage(),
            'items' => $collection->toArray(),
        ];
    }

    private function createPaginator(Collection $collection): LengthAwarePaginator
    {
        $options = $this->getPaginatorOptions();
        $page = $this->getCurrentPage($options);
        $perPage = $this->getItemsPerPage($collection, $options);

        return new LengthAwarePaginator($collection->forPage($page, $perPage), $collection->count(), $perPage, $page, $options);
    }

    private function getPaginatorOptions(): array
    {
        return Paginator::resolveQueryString();
    }

    private function getCurrentPage(array $options): int
    {
        return isset($options['page']) ? (int)$options['page'] : 1;
    }

    private function getItemsPerPage(Collection $collection, array $options): int
    {
        return isset($options['perPage']) ? (int)$options['perPage'] : $collection->count();
    }

    abstract protected function getPresentationData(): ?array;
}
