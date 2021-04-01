<?php

declare(strict_types=1);

namespace Exhum4n\Components\Http\Presenters;

use Illuminate\Http\JsonResponse;

abstract class SimplePresenter
{
    /**
     * @var int
     */
    protected $code = 200;

    /**
     * @return JsonResponse
     */
    public function present(): JsonResponse
    {
        return response()->json($this->getPresentationData(), $this->code);
    }

    /**
     * Response structure.
     *
     * @return array|null
     */
    abstract protected function getPresentationData(): array;
}
