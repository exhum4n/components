<?php

declare(strict_types=1);

namespace Exhum4n\Components\Exceptions;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
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

        return response()->json($errorBody, $this->getCode($e));
    }

    protected function getMessage(Throwable $exception): string
    {
        if ($exception instanceof NotFoundHttpException) {
            return 'endpoint_not_found';
        }

        if ($exception instanceof ValidationException) {
            return 'validation_failed';
        }

        $message = $exception->getMessage();
        if (empty($message)) {
            return 'internal_server_error';
        }

        return $message;
    }

    protected function getDetail(Throwable $exception): string|array
    {
        if ($exception instanceof ValidationException) {
            return json_decode($exception->getMessage(), true);
        }

        return 'no_details';
    }

    protected function getCode(Throwable $exception): int
    {
        $code = $exception->getCode();

        if ($exception instanceof QueryException) {
            return 500;
        }

        if ($code === 0) {
            return Response::HTTP_NOT_FOUND;
        }

        if (is_numeric($code) === false) {
            return Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return (int) $code;
    }
}
