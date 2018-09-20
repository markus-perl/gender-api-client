<?php

namespace GenderApi\Client\Downloader;

use GenderApi\Client\InvalidParameterException;

/**
 * Class Curl
 *
 * @package GenderApi\Client\Downloader
 */
class Curl extends AbstractDownloader
{

    /**
     * @var resource
     */
    private $curl = null;

    /**
     * @param string $url
     *
     * @return bool|string
     * @throws NetworkErrorException
     */
    public function download($url)
    {
        if (!$this->curl) {
            $this->curl = curl_init();
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
                'User-Agent: gender-api-client/1.0',
                'Content-Type: application/json'
            ));
            curl_setopt($this->curl, CURLOPT_TIMEOUT, 5);
            curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);

            if ($this->proxyHost) {
                curl_setopt($this->curl, CURLOPT_PROXY, $this->proxyHost);
                curl_setopt($this->curl, CURLOPT_PROXYPORT, $this->proxyPort);
            }
        }

        curl_setopt($this->curl, CURLOPT_URL, $url);

        $response = @curl_exec($this->curl);
        $responseCode = curl_getinfo($this->curl, CURLINFO_RESPONSE_CODE);

        if ($response === false || $responseCode != 200) {
            $lastError = curl_error($this->curl);
            throw new NetworkErrorException($lastError);
        }

        return $response;
    }

    /**
     * Set a proxy server for every request.
     *
     * @param string|null $host
     * @param int|null $port
     * @throws InvalidParameterException
     */
    public function setProxy($host = null, $port = null)
    {
        parent::setProxy($host, $port);

        if ($this->curl) {
            if ($this->proxyHost) {
                curl_setopt($this->curl, CURLOPT_PROXY, $this->proxyHost);
                curl_setopt($this->curl, CURLOPT_PROXYPORT, $this->proxyPort);
            } else {
                curl_setopt($this->curl, CURLOPT_PROXY, '');
            }
        }
    }

}