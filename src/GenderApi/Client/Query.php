<?php

declare(strict_types=1);

namespace GenderApi\Client;

use GenderApi\Client\Downloader\AbstractDownloader;
use GenderApi\Client\Result\AbstractResult;

/**
 * Handles API v2 query execution
 */
class Query
{
    /** @var array<string, mixed> */
    private array $body = [];

    private string $apiUrl;

    private AbstractDownloader $downloader;

    private string $endpoint;

    private string $httpMethod;

    public function __construct(
        string $apiUrl,
        AbstractDownloader $downloader,
        string $endpoint = '/gender/by-first-name',
        string $httpMethod = 'POST'
    ) {
        $this->apiUrl = rtrim($apiUrl, '/');
        $this->downloader = $downloader;
        $this->endpoint = $endpoint;
        $this->httpMethod = $httpMethod;
    }

    /**
     * Set a body parameter for POST requests
     */
    public function setBodyParam(string $key, mixed $value): void
    {
        if ($value !== null && $value !== '') {
            $this->body[$key] = $value;
        }
    }

    /**
     * Set the entire body (for batch requests)
     *
     * @param array<mixed> $body
     */
    public function setBody(array $body): void
    {
        $this->body = $body;
    }

    /**
     * @return array<string, mixed>
     */
    public function getBody(): array
    {
        return $this->body;
    }

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    public function setEndpoint(string $endpoint): void
    {
        $this->endpoint = $endpoint;
    }

    public function getHttpMethod(): string
    {
        return $this->httpMethod;
    }

    public function setHttpMethod(string $method): void
    {
        $this->httpMethod = $method;
    }

    /**
     * Build the full URL for the request
     */
    public function getUrl(): string
    {
        return $this->apiUrl . $this->endpoint;
    }

    /**
     * Execute the API request
     *
     * @throws RuntimeException
     * @throws ApiException
     */
    public function execute(AbstractResult $result): AbstractResult
    {
        $url = $this->getUrl();
        $body = $this->httpMethod === 'POST' ? $this->body : null;

        $response = $this->downloader->request($url, $this->httpMethod, $body);

        $responseJson = json_decode($response);

        // Handle array responses (for batch endpoints)
        if (is_array($responseJson)) {
            $result->parseResponse((object) ['results' => $responseJson]);
            $result->setQueryUrl($url);
            return $result;
        }

        if (!$responseJson instanceof \stdClass) {
            throw new RuntimeException('Failed to parse Response. Invalid Json: ' . $response);
        }

        // v2 API error handling
        if (isset($responseJson->errmsg)) {
            $errno = isset($responseJson->errno) ? (int) $responseJson->errno : 0;
            throw new ApiException((string) $responseJson->errmsg, $errno);
        }

        $result->parseResponse($responseJson);
        $result->setQueryUrl($url);

        return $result;
    }

    // Legacy compatibility methods
    /** @deprecated Use setBodyParam() instead */
    public function addParam(string $key, string|int|null $value): void
    {
        $this->setBodyParam($key, $value);
    }

    /**
     * @deprecated Use getBody() instead
     * @return array<mixed>
     */
    public function getParams(): array
    {
        return $this->body;
    }

    /** @deprecated Use getEndpoint() instead */
    public function getMethod(): string
    {
        return $this->endpoint;
    }

    /** @deprecated Use setEndpoint() instead */
    public function setMethod(string $method): void
    {
        $this->endpoint = $method;
    }
}