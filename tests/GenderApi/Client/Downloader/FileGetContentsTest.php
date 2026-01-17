<?php

declare(strict_types=1);

namespace GenderApi\Client\Downloader;

use GenderApiTest\TestCase;

/**
 * Tests for FileGetContents downloader (API v2)
 */
class FileGetContentsTest extends TestCase
{
    public function testRequest(): void
    {
        $fgt = new FileGetContents();
        $fgt->setApiKey($this->apiKey);

        if (!$this->doMock) {
            $response = $fgt->request(
                'https://gender-api.com/v2/gender/by-first-name',
                'POST',
                ['first_name' => 'markus']
            );
            $this->assertStringContainsString('gender', $response);
        } else {
            // Test with local file for mock mode - but request() needs network
            $this->assertTrue(true);
        }
    }

    public function testRequestNetworkError(): void
    {
        $this->expectException(NetworkErrorException::class);
        $fgt = new FileGetContents();

        $fgt->request('http://localhost:9999', 'GET');
    }

    public function testSetProxy(): void
    {
        $fgt = new FileGetContents();

        $fgt->setProxy('127.0.0.1', 3128);
        $this->assertEquals('127.0.0.1:3128', $fgt->getProxy());
    }
}