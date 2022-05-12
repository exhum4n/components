<?php

/**
 * @noinspection PhpUnused
 * @noinspection PhpPossiblePolymorphicInvocationInspection
 */

declare(strict_types=1);

namespace Exhum4n\Components\Tools;

use Exhum4n\Components\Http\Response;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class HttpClient
{
    public Client $client;

    protected string $method = 'GET';

    protected ?string $authorization = null;

    protected array $headers = [];

    protected array $formParams = [];

    protected array $queryParams = [];

    protected array $jsonBody = [];

    protected array|string $body;

    protected ResponseInterface $response;

    private array $options = [];

    public function __construct(string $baseUri = '')
    {
        $this->client = new Client([
            'base_uri' => $baseUri,
            'cookies' => true,
            'timeout' => 60,
        ]);
    }

    public function setQueryParams(array $queryParams): HttpClient
    {
        $this->queryParams = array_merge($this->queryParams, $queryParams);

        return $this;
    }

    public function setHeaders(array $headers = []): HttpClient
    {
        $this->headers = array_merge($this->headers, $headers);

        return $this;
    }

    public function setJsonBody(array $json): HttpClient
    {
        $this->jsonBody = array_merge($this->jsonBody, $json);

        return $this;
    }

    public function setFormParams(array $params): HttpClient
    {
        $this->formParams = array_merge($this->formParams, $params);

        return $this;
    }

    public function setBody(array|string $body): HttpClient
    {
        $this->body = $body;

        return $this;
    }

    public function setOptions(array $params): HttpClient
    {
        $this->options = array_merge($this->options, $params);

        return $this;
    }

    public function setBearerToken(string $token): void
    {
        $this->authorization = "Bearer $token";
    }

    /**
     * @throws GuzzleException
     */
    public function get(string $uri): Response
    {
        $this->setMethod('GET');

        return $this->go($uri);
    }

    /**
     * @throws GuzzleException
     */
    public function post(string $uri): Response
    {
        $this->setMethod('POST');

        return $this->go($uri);
    }

    /**
     * @throws GuzzleException
     */
    public function delete(string $uri): Response
    {
        $this->setMethod('DELETE');

        return $this->go($uri);
    }

    /**
     * @throws GuzzleException
     */
    public function patch(string $uri): Response
    {
        $this->setMethod('PATCH');

        return $this->go($uri);
    }

    /**
     * @throws GuzzleException
     */
    public function put(string $uri): Response
    {
        $this->setMethod('PUT');

        return $this->go($uri);
    }

    /**
     * @throws GuzzleException
     */
    protected function go(string $uri = '/'): Response
    {
        if (empty($this->jsonBody) === false) {
            $this->options['json'] = $this->jsonBody;
        }

        if (empty($this->formParams) === false) {
            $this->options['form_params'] = $this->formParams;
        }

        if (empty($this->body) === false) {
            $this->options['body'] = $this->body;
        }

        if (empty($this->queryParams) === false) {
            $this->options['query'] = $this->queryParams;
        }

        $this->options['headers'] = $this->getDefaultHeaders();
        if ($this->headers !== null) {
            $this->options['headers'] = array_merge($this->headers, $this->options['headers']);
        }

        $this->response = $this->client->request($this->method, $uri, $this->options);

        $this->reset();

        return new Response($this->response);
    }

    protected function reset(): void
    {
        $this->queryParams = [];
        $this->jsonBody = [];
        $this->formParams = [];

        $this->headers = $this->getDefaultHeaders();
    }

    protected function getDefaultHeaders(): array
    {
        $headers = [];

        if ($this->authorization !== null) {
            $headers['Authorization'] = $this->authorization;
        }

        return $headers;
    }

    final function setMethod(string $method): HttpClient
    {
        $this->method = $method;

        return $this;
    }
}
