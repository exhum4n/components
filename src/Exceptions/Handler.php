<?php

declare(strict_types=1);

namespace Exhum4n\Components\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
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

    /**
     * @param Request $request
     * @param Throwable $e
     *
     * @return JsonResponse|Response
     */
    public function render($request, Throwable $e)
    {
        $exceptionCode = $this->getExceptionCode($e);

        $errorBody = [
            'code' => $exceptionCode,
            'message' => $e->getMessage(),
        ];

        if ($e instanceof NotFoundHttpException) {
            $errorBody = [
                'code' => Response::HTTP_NOT_FOUND,
                'message' => 'Endpoint not found',
            ];
        }

        if ($this->isValidationException($e)) {
            $errorBody['message'] = 'Validation failed.';
            $errorBody['errors'] = json_decode($e->getMessage(), true);
        }

        if ($this->isAuthenticationException($e)) {
            $errorBody['code'] = Response::HTTP_UNAUTHORIZED;
        }

        if (config('app.debug')) {
            $errorBody['trace'] = $e->getTrace();
        }

        return response()->json($errorBody, $exceptionCode);
    }

    /**
     * @param Throwable $exception
     *
     * @return int
     */
    protected function getExceptionCode(Throwable $exception): int
    {
        $code = $exception->getCode();

        if (empty($code) || (is_numeric($code) === false)) {
            return Response::HTTP_FORBIDDEN;
        }

        return intval($code);
    }

    /**
     * @param Throwable $exception
     *
     * @return bool
     */
    protected function isValidationException(Throwable $exception): bool
    {
        return $exception instanceof ValidationException;
    }

    /**
     * @param Throwable $exception
     *
     * @return bool
     */
    protected function isAuthenticationException(Throwable $exception): bool
    {
        return $exception instanceof AuthenticationException;
    }
}
