<?php

declare(strict_types=1);

namespace GenderApi\Client\Downloader;

use CurlHandle;
use GenderApi\Client\InvalidArgumentException;

/**
 * cURL-based HTTP downloader for API v2
 */
class Curl extends AbstractDownloader
{
    private ?CurlHandle $curl = null;

    /**
     * @throws NetworkErrorException
     */
    public function request(string $url, string $method = 'GET', ?array $body = null): string
    {
        if ($this->curl === null) {
            $this->curl = curl_init();
            curl_setopt($this->curl, CURLOPT_TIMEOUT, 10);
            curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);

            if ($this->proxyHost !== null) {
                curl_setopt($this->curl, CURLOPT_PROXY, $this->proxyHost);
                curl_setopt($this->curl, CURLOPT_PROXYPORT, $this->proxyPort);
            }
        }

        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->getAuthHeaders());

        if ($method === 'POST') {
            curl_setopt($this->curl, CURLOPT_POST, true);
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $body !== null ? json_encode($body) : '{}');
        } else {
            curl_setopt($this->curl, CURLOPT_POST, false);
            curl_setopt($this->curl, CURLOPT_HTTPGET, true);
        }

        $response = curl_exec($this->curl);
        $responseCode = curl_getinfo($this->curl, CURLINFO_RESPONSE_CODE);

        if ($response === false) {
            $lastError = curl_error($this->curl);
            throw new NetworkErrorException($lastError . ' - ' . $url);
        }

        // v2 API may return error responses with non-200 status
        if ($responseCode >= 400) {
            throw new NetworkErrorException('HTTP ' . $responseCode . ' - ' . $url . ' - ' . $response);
        }

        return (string) $response;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function setProxy(?string $host = null, ?int $port = null): void
    {
        parent::setProxy($host, $port);

        if ($this->curl !== null) {
            if ($this->proxyHost !== null) {
                curl_setopt($this->curl, CURLOPT_PROXY, $this->proxyHost);
                curl_setopt($this->curl, CURLOPT_PROXYPORT, $this->proxyPort);
            } else {
                curl_setopt($this->curl, CURLOPT_PROXY, '');
            }
        }
    }
}