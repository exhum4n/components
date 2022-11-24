<?php

/**
 * @noinspection PhpUnused
 * @noinspection PhpUnhandledExceptionInspection
 */

declare(strict_types=1);

namespace Exhum4n\Components\Http;

use Exhum4n\Components\Repositories\RequestCacheRepository;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response as BaseResponse;
use Psr\Http\Message\ResponseInterface;

class Client
{
    public GuzzleClient $client;

    protected GuzzleException $exception;

    protected string $method = 'GET';

    protected string $baseUri = '';

    protected array $headers = [];

    protected array $formParams = [];

    protected array $queryParams = [];

    protected array $jsonBody = [];

    protected array|string $body;

    protected ResponseInterface $response;

    protected RequestCacheRepository $requestCache;

    private array $options = [];

    private int $cacheLifetime;

    private string $requestHash;

    private bool $isCached = false;

    public function __construct(?string $baseUri = '')
    {
        $this->client = new GuzzleClient([
            'base_uri' => $baseUri,
            'cookies' => true,
            'timeout' => 60,
        ]);

        $this->requestCache = new RequestCacheRepository();
    }

    public function setQueryParams(array $queryParams): Client
    {
        $this->queryParams = array_merge($this->queryParams, $queryParams);

        return $this;
    }

    public function setHeaders(array $headers = []): Client
    {
        $this->headers = array_merge($this->headers, $headers);

        return $this;
    }

    public function setJsonBody(array $json): Client
    {
        $this->jsonBody = array_merge($this->jsonBody, $json);

        return $this;
    }

    public function setFormParams(array $params): Client
    {
        $this->formParams = array_merge($this->formParams, $params);

        return $this;
    }

    public function setBody(array|string $body): Client
    {
        $this->body = $body;

        return $this;
    }

    public function setOptions(array $options): Client
    {
        $this->options = array_merge($this->options, $options);

        return $this;
    }

    public function setBearerToken(string $token): void
    {
        $this->setHeaders([
            'Authorization' => "Bearer $token"
        ]);
    }

    public function setBasicAuth(string $token): void
    {
        $this->setHeaders([
            'Authorization' => "Basic $token"
        ]);
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

    public function cacheRequest(int $seconds = 60): Client
    {
        $this->cacheLifetime = $seconds;
        $this->isCached = true;

        return $this;
    }

    /**
     * @throws GuzzleException
     */
    protected function go(string $uri = '/'): Response
    {
        $this->fillOptions();

        if ($this->isRequestCached($uri)) {
            return $this->getCachedResponse();
        }

        try {
            $this->response = $this->client->request($this->method, $uri, $this->options);
        } catch (GuzzleException $exception) {
            $this->handleFailRequest($exception);
        }

        $response = new Response($this->response);

        $this->handleSuccessRequest($uri, $response);

        return $response;
    }

    protected function getCachedResponse(): Response
    {
        $cache = $this->requestCache->get($this->requestHash);

        return new Response(new BaseResponse(body: $cache));
    }

    protected function handleSuccessRequest(string $uri, Response $response): void
    {
        if ($this->isCached) {
            $requestHash = $this->makeRequestHash($uri);

            $this->requestCache->setExpirationTime($this->cacheLifetime);
            $this->requestCache->set($requestHash, $response->getBody());
        }

        $this->reset();
    }

    /**
     * @throws GuzzleException
     */
    protected function handleFailRequest(GuzzleException $exception): void
    {
        throw $exception;
    }

    protected function fillOptions(): void
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

        if (empty($this->headers) === false) {
            $this->options['headers'] = $this->headers;
        }
    }

    protected function reset(): void
    {
        $this->queryParams = [];
        $this->jsonBody = [];
        $this->formParams = [];
        $this->body = [];
        $this->isCached = false;
    }

    protected function isRequestCached(string $url): bool
    {
        $this->requestHash = $this->makeRequestHash($url);

        return (bool) $this->requestCache->get($this->requestHash);
    }

    protected function makeRequestHash(string $uri): string
    {
        $requestJson = json_encode([
            'url' => $this->baseUri . '/' . $uri,
            'method' => $this->method,
            'json' => $this->jsonBody,
            'form_params' => $this->formParams,
            'query' => $this->queryParams,
        ], JSON_THROW_ON_ERROR);

        return base64_encode($requestJson);
    }

    final public function setMethod(string $method): void
    {
        $this->method = $method;
    }
}
