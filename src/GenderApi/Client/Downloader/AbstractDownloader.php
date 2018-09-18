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
     * reset proxy settings for current resource
     */
    abstract public function resetProxy();

    /**
     * @param bool $enabled
     * @param string $host
     * @param int $port
     * @throws InvalidParameterException
     */
    public function setProxy($enabled, $host, $port)
    {
        if (!is_bool($enabled)) {
            throw new InvalidParameterException('Invalid Parameter for $enabled. Bool expected, ' . gettype($enabled) . ' given.');
        }

        if (!is_string($host)) {
            throw new InvalidParameterException('Invalid Parameter for $host. String expected, ' . gettype($host) . ' given.');
        }

        if (!is_int($port)) {
            throw new InvalidParameterException('Invalid Parameter for $port. Int expected, ' . gettype($port) . ' given.');
        }

        $this->useProxy = !!$enabled;
        $this->proxyHost = $host;
        $this->proxyPort = $port;
        $this->resetProxy();
    }
}