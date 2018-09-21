<?php

namespace GenderApi\Client\Downloader;

use \GenderApi\Client\InvalidArgumentException;

/**
 * Class AbstractDownloader
 * @package GenderApi\Client\Downloader
 *
 */
abstract class AbstractDownloader
{

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
     * @throws InvalidArgumentException
     */
    public function setProxy($host = null, $port = null)
    {
        if (!$host) {
            $this->proxyHost = null;
            $this->proxyPort = null;
            return;
        }

        if (!is_string($host)) {
            throw new InvalidArgumentException('host expects a string, ' . gettype($host) . ' given.');
        }

        if (!is_int($port)) {
            throw new InvalidArgumentException('port expects an integer, ' . gettype($port) . ' given.');
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