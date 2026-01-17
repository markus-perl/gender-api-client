<?php

declare(strict_types=1);

namespace GenderApi\Client;

use GenderApi\Client;
use GenderApi\Client\Downloader\FileGetContents;
use GenderApiTest\TestCase;

/**
 * Tests for Multiple Names lookup (API v2)
 */
class MultipleNamesTest extends TestCase
{
    public function testGetByMultipleNamesWithoutCountry(): void
    {
        $genderApiClient = $this->getClient();

        if ($this->doMock) {
            $downloader = $this->createMock(FileGetContents::class);
            $downloader->method('request')
                ->willReturn('[{"input":{"first_name":"stefan","id":"1"},"details":{"credits_used":1,"duration":"25ms","samples":26494},"result_found":true,"first_name":"Stefan","probability":0.99,"gender":"male"},{"input":{"first_name":"elisabeth","id":"2"},"details":{"credits_used":1,"duration":"25ms","samples":17000},"result_found":true,"first_name":"Elisabeth","probability":0.99,"gender":"female"}]');
            $genderApiClient->setDownloader($downloader);
        }

        $result = $genderApiClient->getByMultipleNames(['stefan', 'elisabeth']);

        $this->assertCount(2, $result);

        $names = [];
        foreach ($result as $singleResult) {
            $this->assertNotNull($singleResult->getFirstName());
            $this->assertNotNull($singleResult->getGender());
            $names[$singleResult->getFirstName()] = $singleResult->getGender();
        }

        if ($this->doMock) {
            $this->assertArrayHasKey('Stefan', $names);
            $this->assertEquals('male', $names['Stefan']);
            $this->assertArrayHasKey('Elisabeth', $names);
            $this->assertEquals('female', $names['Elisabeth']);
        }
    }

    public function testGetByMultipleNamesWithCountry(): void
    {
        $genderApiClient = $this->getClient();

        if ($this->doMock) {
            $downloader = $this->createMock(FileGetContents::class);
            $downloader->method('request')
                ->willReturn('[{"input":{"first_name":"andrea","country":"IT","id":"1"},"details":{"credits_used":1,"duration":"25ms","samples":5000,"country":"IT"},"result_found":true,"first_name":"Andrea","probability":0.90,"gender":"male"}]');
            $genderApiClient->setDownloader($downloader);
        }

        $result = $genderApiClient->getByMultipleNamesAndCountry(['andrea'], 'IT');

        $this->assertGreaterThanOrEqual(1, count($result));

        if ($this->doMock) {
            $first = $result->current();
            $this->assertEquals('male', $first->getGender());
        }
    }
}