<?php

declare(strict_types=1);

namespace GenderApi\Client\Downloader;

/**
 * file_get_contents-based HTTP downloader for API v2 (fallback when curl is unavailable)
 */
class FileGetContents extends AbstractDownloader
{
    /**
     * @throws NetworkErrorException
     */
    public function request(string $url, string $method = 'GET', ?array $body = null): string
    {
        $headers = $this->getAuthHeaders();

        $options = [
            'http' => [
                'method' => $method,
                'header' => implode("\r\n", $headers),
                'timeout' => 10,
                'ignore_errors' => true, // Get response body even on error
            ]
        ];

        if ($method === 'POST') {
            $options['http']['content'] = $body !== null ? json_encode($body) : '{}';
        }

        if ($this->proxyHost !== null) {
            $options['http']['proxy'] = 'tcp://' . $this->proxyHost . ':' . $this->proxyPort;
            $options['http']['request_fulluri'] = true;
        }

        $context = stream_context_create($options);

        $response = @file_get_contents($url, false, $context);
        if ($response === false) {
            $lastError = error_get_last();
            $message = is_array($lastError)
                ? $lastError['message']
                : 'Unknown error';
            throw new NetworkErrorException($message . ' - ' . $url);
        }

        // Check for HTTP errors in response headers
        $responseHeaders = http_get_last_response_headers();
        if ($responseHeaders !== null) {
            $statusLine = $responseHeaders[0] ?? '';
            if (preg_match('/HTTP\/\d\.\d\s+(\d+)/', $statusLine, $matches)) {
                $statusCode = (int) $matches[1];
                if ($statusCode >= 400) {
                    throw new NetworkErrorException('HTTP ' . $statusCode . ' - ' . $url . ' - ' . $response);
                }
            }
        }

        return $response;
    }
}