<?php

declare(strict_types=1);

namespace GenderApi\Client\Downloader;

use GenderApi\Client\InvalidArgumentException;

/**
 * Abstract base class for HTTP downloaders
 */
abstract class AbstractDownloader
{
    protected ?string $proxyHost = null;

    protected ?int $proxyPort = null;

    protected ?string $apiKey = null;

    /**
     * Execute an HTTP request
     *
     * @param string $url The URL to request
     * @param string $method HTTP method (GET or POST)
     * @param array<string, mixed>|null $body JSON body for POST requests
     * @return string Response body
     * @throws NetworkErrorException
     */
    abstract public function request(string $url, string $method = 'GET', ?array $body = null): string;

    /**
     * Set the API key for Bearer authentication
     */
    public function setApiKey(string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Get the API key
     */
    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    /**
     * Set a proxy server for every request.
     *
     * @throws InvalidArgumentException
     */
    public function setProxy(?string $host = null, ?int $port = null): void
    {
        if ($host === null) {
            $this->proxyHost = null;
            $this->proxyPort = null;
            return;
        }

        $this->proxyHost = $host;
        $this->proxyPort = $port ?? 3128;
    }

    public function getProxy(): ?string
    {
        if ($this->proxyHost !== null) {
            return $this->proxyHost . ':' . $this->proxyPort;
        }

        return null;
    }

    /**
     * Build authorization headers
     *
     * @return array<string>
     */
    protected function getAuthHeaders(): array
    {
        $headers = [
            'User-Agent: gender-api-client/2.0',
            'Content-Type: application/json',
            'Accept: application/json',
        ];

        if ($this->apiKey !== null) {
            $headers[] = 'Authorization: Bearer ' . $this->apiKey;
        }

        return $headers;
    }
}