<?php

namespace GenderApi\Client\Downloader;

use GenderApi\Client\InvalidParameterException;

/**
 * Class AbstractDownloader
 * @package GenderApi\Client\Downloader
 *
 */
abstract class AbstractDownloader
{
    /**
     * @var bool
     */
    protected $useProxy = false;

    /**
     * @var string
     */
    protected $proxyHost = '';

    /**
     * @var int
     */
    protected $proxyPort = 3128;

    /**
     * @param string $url
     * @return string
     */
    abstract public function download($url);

    /**
     * Set a proxy server for every request.
     *
     * @param string|null $host
     * @param int|null $port
     * @throws InvalidParameterException
     */
    public function setProxy($host = null, $port = null)
    {
        if (!$host) {
            $this->proxyHost = null;
            $this->proxyPort = null;
            return;
        }

        if (!is_string($host)) {
            throw new InvalidParameterException('Invalid Parameter for $host. String expected, ' . gettype($host) . ' given.');
        }

        if (!is_int($port)) {
            throw new InvalidParameterException('Invalid Parameter for $port. Int expected, ' . gettype($port) . ' given.');
        }

        $this->proxyHost = $host;
        $this->proxyPort = $port;
    }

    /**
     * @return string|null
     */
    public function getProxy()
    {
        if ($this->proxyHost) {
            return $this->proxyHost . ':' . $this->proxyPort;
        }

        return null;
    }
}