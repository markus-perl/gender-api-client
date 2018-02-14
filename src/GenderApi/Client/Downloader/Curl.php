<?php

namespace GenderApi\Client\Downloader;

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
        if ( ! $this->curl) {
            $this->curl = curl_init();
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
                'User-Agent: gender-api-client/1.0',
                'Content-Type: application/json'
            ));
            curl_setopt($this->curl, CURLOPT_TIMEOUT, 5);
            curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
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
}