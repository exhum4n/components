<?php

declare(strict_types=1);

namespace Exhum4n\Components\Tools;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\TransferStats;

class HttpClient
{
    protected Client $client;

    protected string $method = 'GET';

    protected array $headers = [];

    protected string $authorization = '';

    protected array $params = [];

    protected Response $response;

    protected TransferStats $stats;

    protected array $json = [];

    private array $requestOptions = [];

    public function __construct(string $baseUri = '')
    {
        $this->client = new Client([
            'base_uri' => $baseUri,
            'cookies' => true,
            'timeout' => 60
        ]);
    }

    public function getStats(): TransferStats
    {
        return $this->stats;
    }

    public function setParams(array $params): HttpClient
    {
        $this->params = array_merge($this->params, $params);

        return $this;
    }

    public function setHeaders(array $headers = []): HttpClient
    {
        $this->headers = array_merge($this->headers, $headers);

        return $this;
    }

    public function setJson(array $json): HttpClient
    {
        $this->json = array_merge($this->json, $json);

        return $this;
    }

    public function authorize(string $token): void
    {
        $this->authorization = "Bearer $token";
    }

    public function get(string $uri): ?array
    {
        $this->setMethod('GET');

        return $this->go($uri);
    }

    public function post(string $uri): ?array
    {
        $this->setMethod('POST');

        return $this->go($uri);
    }

    /**
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     */
    protected function go(string $uri = '/', bool $debug = false): ?array
    {
        $defaultOptions = $this->getDefaultRequestOptions($debug);

        if ($this->json !== null) {
            $this->requestOptions['json'] = $this->json;
        }

        $this->requestOptions['headers'] = $this->getDefaultHeaders();
        if ($this->headers !== null) {
            $this->requestOptions['headers'] = array_merge($this->headers, $this->requestOptions['headers']);
        }

        if ($this->params !== null) {
            $this->requestOptions['query'] = $this->params;
        }

        $this->requestOptions += $defaultOptions;

        try {
            $response = $this->client->request($this->method, $uri, $this->requestOptions);
        } catch (GuzzleException $exception) {
            return $exception->getResponse();
        }

        $this->response = $response;

        $this->reset();

        return $this->getJsonResponse();
    }

    public function getStatusCode(): int
    {
        return $this->response->getStatusCode();
    }

    public function getJsonResponse(): ?array
    {
        return json_decode($this->getResponse(), true);
    }

    public function getResponse(): string
    {
        return (string) $this->response->getBody();
    }

    public function reset(): void
    {
        $this->params = [];
        $this->json = [];

        $this->headers = $this->getDefaultHeaders();
    }

    public function setMethod(string $method): HttpClient
    {
        $this->method = $method;

        return $this;
    }

    private function getDefaultHeaders(): array
    {
        $headers = [
            'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko)' .
                ' Chrome/83.0.4103.116 Safari/537.36',
        ];

        if ($this->authorization !== null) {
            $headers['Authorization'] = $this->authorization;
        }

        return $headers;
    }

    private function getDefaultRequestOptions(bool $debug): array
    {
        return [
            'debug' => $debug,
            'form_params' => $this->params,
            'on_stats' => function (TransferStats $stats): void {
                $this->stats = $stats;
            },
            'allow_redirects' => [
                'max' => 10
            ]
        ];
    }
}
