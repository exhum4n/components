<?php

declare(strict_types=1);

namespace Exhum4n\Components\Http\Presenters;

use Illuminate\Http\JsonResponse;

abstract class SimplePresenter
{
    protected int $code = 200;

    public function present(): JsonResponse
    {
        return response()->json($this->getPresentationData(), $this->code);
    }

    abstract protected function getPresentationData(): array;
}
