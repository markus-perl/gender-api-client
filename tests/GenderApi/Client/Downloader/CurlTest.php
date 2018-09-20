<?php

namespace GenderApi\Client\Downloader;

use GenderApiTest\TestCase;

/**
 * Class CurlTest
 * @package GenderApi\Client\Downloader
 */
class CurlTest extends TestCase
{

    /**
     *
     */
    public function testDownload()
    {
        $curl = new Curl();

        if (!$this->doMock) {
            $response = $curl->download('https://gender-api.com/get?name=markus&key=' . $this->apiKey);
            $this->assertContains('gender":"male"', $response);
        }
    }

    /**
     * @expectedException \GenderApi\Client\Downloader\NetworkErrorException
     */
    public function testDownloadNetworkError()
    {
        $curl = new Curl();

        if ($this->doMock) {
            $curl->download('http://localhost:9999');
        } else {
            $curl->download('https://gender-api.com/invalid?name=markus&key=' . $this->apiKey);
        }
    }

    public function testSetProxy()
    {
        $curl = new Curl();

        $curl->setProxy('127.0.0.1', 3128);
        $this->assertEquals('127.0.0.1:3128', $curl->getProxy());
    }

}