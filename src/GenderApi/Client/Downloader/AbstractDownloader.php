<?php

namespace GenderApi\Client\Downloader;

/**
 * Class AbstractDownloader
 * @package GenderApi\Client\Downloader
 *
 */
abstract class AbstractDownloader
{
    /**
     * @param string $url
     * @return string
     */
    abstract public function download($url);
}