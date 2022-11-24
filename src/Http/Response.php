<?php

/**
 * @noinspection PhpUnused
 * @noinspection PhpUnhandledExceptionInspection
 */

declare(strict_types=1);

namespace Exhum4n\Components\Http;

use Symfony\Component\HttpFoundation\Response as Responses;
use Psr\Http\Message\ResponseInterface;

class Response
{
    protected ResponseInterface $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function isSuccess(): bool
    {
        return $this->response->getStatusCode() === Responses::HTTP_OK;
    }

    public function getStatusCode(): int
    {
        return $this->response->getStatusCode();
    }

    public function getJson(): ?array
    {
        return json_decode($this->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }

    public function getBody(): string
    {
        return (string) $this->response->getBody();
    }
}
