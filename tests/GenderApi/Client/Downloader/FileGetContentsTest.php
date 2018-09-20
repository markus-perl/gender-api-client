<?php

namespace GenderApi\Client\Downloader;

use GenderApiTest\TestCase;

/**
 * Class FileGetContentsTest
 * @package GenderApi\Client\Downloader
 */
class FileGetContentsTest extends TestCase
{

    /**
     *
     */
    public function testDownload()
    {
        $fgt = new FileGetContents();

        if ($this->doMock) {
            $response = $fgt->download(__DIR__ . '/download.json');
        } else {
            $response = $fgt->download('https://gender-api.com/get?name=markus&key=' . $this->apiKey);
        }

        $this->assertContains('gender":"male"', $response);
    }

    /**
     * @expectedException \GenderApi\Client\Downloader\NetworkErrorException
     */
    public function testDownloadNetworkError()
    {
        $fgt = new FileGetContents();

        if ($this->doMock) {
            $fgt->download(__DIR__ . '/invalid.json');
        } else {
            $fgt->download('https://gender-api.com/invalid?name=markus&key=' . $this->apiKey);
        }
    }

    public function testSetProxy()
    {
        $curl = new Curl();

        $curl->setProxy('127.0.0.1', 3128);
        $this->assertEquals('127.0.0.1:3128', $curl->getProxy());
    }

}