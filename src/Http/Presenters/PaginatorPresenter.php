<?php

/**
 * @noinspection PhpUnused
 */

declare(strict_types=1);

namespace Exhum4n\Components\Http\Presenters;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use JetBrains\PhpStorm\ArrayShape;

abstract class PaginatorPresenter extends SimplePresenter
{
    protected LengthAwarePaginator $items;

    public function __construct(LengthAwarePaginator $items)
    {
        $this->items = $items;
    }

    public function present(): JsonResponse
    {
        $presentationData = array_merge($this->getPaginator(), $this->getItems());

        return response()->json($presentationData, $this->code);
    }

    #[ArrayShape([
        'items' => "array"
    ])]
    private function getItems(): array
    {
        return [
            'items' => $this->getPresentationData()['data']
        ];
    }

    #[ArrayShape([
        'total' => "int",
        'nextPageUrl' => "null|string",
        'previousPageUrl' => "null|string",
        'currentPage' => "int",
        'lastPage' => "int",
        'perPage' => "int",
        'path' => "null|string"
    ])]
    private function getPaginator(): array
    {
        return [
            'total' => $this->items->total(),
            'nextPageUrl' => $this->items->nextPageUrl(),
            'previousPageUrl' => $this->items->previousPageUrl(),
            'currentPage' => $this->items->currentPage(),
            'lastPage' => $this->items->lastPage(),
            'perPage' => $this->items->perPage(),
            'path' => $this->items->path(),
        ];
    }
}
