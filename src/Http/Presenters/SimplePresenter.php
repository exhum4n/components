<?php

declare(strict_types=1);

namespace Exhum4n\Components\Http\Presenters;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

abstract class SimplePresenter
{
    public function present(int $code = 200): JsonResponse|Response
    {
        $presentationData = $this->getPresentationData();
        if (is_null($presentationData)) {
            return response(status: $code);
        }

        return response()->json($this->getPresentationData(), $code);
    }

    abstract protected function getPresentationData(): ?array;
}
