<?php

declare(strict_types=1);

namespace Exhum4n\Components\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * @param Throwable $e
     *
     * @throws Throwable
     */
    public function report(Throwable $e): void
    {
        parent::report($e);
    }

    public function render($request, Throwable $e): JsonResponse
    {
        $errorBody = [
            'error' => $this->getMessage($e),
            'detail' => $this->getDetail($e),
        ];

        if (config('app.debug')) {
            $errorBody['trace'] = $e->getTrace();
        }

        return response()->json($errorBody, $e->getCode());
    }

    protected function getMessage(Throwable $exception): string
    {
        if ($exception instanceof NotFoundHttpException) {
            return 'endpoint_not_found';
        }

        $message = $exception->getMessage();
        if (empty($message)) {
            return 'internal_server_error';
        }

        return $message;
    }

    protected function getDetail(Throwable $exception): string
    {
        if ($exception instanceof ValidationException) {
            return json_decode($exception->getMessage(), true);
        }

        return 'no_details';
    }
}
