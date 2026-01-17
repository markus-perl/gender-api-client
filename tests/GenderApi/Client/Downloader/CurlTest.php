<?php

declare(strict_types=1);

namespace GenderApi\Client\Downloader;

use GenderApiTest\TestCase;

/**
 * Tests for Curl downloader (API v2)
 */
class CurlTest extends TestCase
{
    public function testRequest(): void
    {
        $curl = new Curl();
        $curl->setApiKey($this->apiKey);

        if (!$this->doMock) {
            $response = $curl->request(
                'https://gender-api.com/v2/gender/by-first-name',
                'POST',
                ['first_name' => 'markus']
            );
            $this->assertStringContainsString('gender', $response);
            $this->assertStringContainsString('male', $response);
        } else {
            // Skip when mocking - we can't mock the internal curl operations
            $this->assertTrue(true);
        }
    }

    public function testRequestNetworkError(): void
    {
        $this->expectException(NetworkErrorException::class);
        $curl = new Curl();

        $curl->request('http://localhost:9999', 'GET');
    }

    public function testSetProxy(): void
    {
        $curl = new Curl();

        $curl->setProxy('127.0.0.1', 3128);
        $this->assertEquals('127.0.0.1:3128', $curl->getProxy());
    }
}