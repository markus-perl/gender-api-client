<?php

namespace GenderApi\Client;

use GenderApi\Client\Downloader\AbstractDownloader;
use GenderApi\Client\Result\AbstractResult;

/**
 * Class Query
 *
 * @package GenderApi\Client
 */
class Query
{

    /**
     * @var String[]
     */
    private $params = array();

    /**
     * @var string
     */
    private $apiUrl;

    /**
     * @var AbstractDownloader
     */
    private $downloader;

    /**
     * @var string
     */
    private $method;

    /**
     * Query constructor.
     *
     * @param string $apiUrl
     */
    public function __construct($apiUrl, AbstractDownloader $downloader, $method = 'get')
    {
        $this->apiUrl     = $apiUrl;
        $this->downloader = $downloader;
        $this->method     = 'get';
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function addParam($key, $value)
    {
        $this->params[$key] = $value;
    }

    /**
     * @return String[]
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @param AbstractResult $result
     *
     * @return AbstractResult
     * @throws RuntimeException
     * @throws ApiException
     */
    public function execute(AbstractResult $result)
    {
            $query = http_build_query(
                $this->getParams()
            );

            $url      = $this->apiUrl . $this->getMethod() . '?' . $query;
            $response = $this->downloader->download($url);

            $responseJson = json_decode($response);
            if ( ! $responseJson) {
                throw new RuntimeException('Failed to parse Response. Invalid Json: ' . $response);
            }

            if (isset($responseJson->errno)) {
                throw new ApiException($responseJson->errmsg, $responseJson->errno);
            }

            $result->parseResponse($responseJson);
            $result->setQueryUrl($url);

            return $result;
    }
}