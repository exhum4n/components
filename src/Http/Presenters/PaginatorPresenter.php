<?php

declare(strict_types=1);

namespace Exhum4n\Components\Http\Presenters;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

abstract class PaginatorPresenter extends SimplePresenter
{
    /**
     * @var LengthAwarePaginator
     */
    protected $items;

    /**
     * @param LengthAwarePaginator $items
     */
    public function __construct(LengthAwarePaginator $items)
    {
        $this->items = $items;
    }

    /**
     * @return JsonResponse
     */
    public function present(): JsonResponse
    {
        $presentationData = array_merge($this->getPaginator(), $this->getItems());

        return response()->json($presentationData, $this->code);
    }

    /**
     * @return array
     */
    private function getItems(): array
    {
        return [
            'items' => $this->getPresentationData()
        ];
    }

    /**
     * @return array
     */
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
