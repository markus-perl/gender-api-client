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
            $this->assertStringContainsString('gender":"male"', $response);
        } else {
            $this->markTestSkipped('This test is only executed with an API key');
        }
    }

    public function testDownloadNetworkError()
    {
        $this->expectException(\GenderApi\Client\Downloader\NetworkErrorException::class);
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