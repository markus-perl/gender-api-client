<?php

declare(strict_types=1);

namespace GenderApi\Client;

use GenderApi\Client;
use GenderApi\Client\Downloader\FileGetContents;
use GenderApiTest\TestCase;

/**
 * Tests for single first name lookup (API v2)
 */
class SingleNameTest extends TestCase
{
    public function testGetByFirstNameWithoutCountry(): void
    {
        $genderApiClient = $this->getClient();

        if ($this->doMock) {
            $downloader = $this->createMock(FileGetContents::class);
            $downloader->method('request')
                ->willReturn('{"input":{"first_name":"markus"},"details":{"credits_used":1,"duration":"25ms","samples":26494},"result_found":true,"first_name":"Markus","probability":0.99,"gender":"male"}');
            $genderApiClient->setDownloader($downloader);
        }

        $result = $genderApiClient->getByFirstName('markus');
        $this->assertStringContainsStringIgnoringCase('markus', $result->getFirstName());
        $this->assertEquals('male', $result->getGender());
        $this->assertGreaterThan(90, $result->getAccuracy());

        if ($this->doMock) {
            $this->assertEquals(26494, $result->getSamples());
            $this->assertEquals(25, $result->getDurationInMs());
        }
    }

    public function testGetByFirstNameWithCountry(): void
    {
        $genderApiClient = $this->getClient();

        if ($this->doMock) {
            $downloader = $this->createMock(FileGetContents::class);
            $downloader->method('request')
                ->willReturn('{"input":{"first_name":"tanja","country":"IT"},"details":{"credits_used":1,"duration":"25ms","samples":26494,"country":"IT"},"result_found":true,"first_name":"Tanja","probability":0.98,"gender":"female"}');
            $genderApiClient->setDownloader($downloader);
        }

        $result = $genderApiClient->getByFirstNameAndCountry('tanja', 'IT');
        $this->assertStringContainsStringIgnoringCase('tanja', $result->getFirstName());
        $this->assertEquals('female', $result->getGender());
        $this->assertGreaterThan(90, $result->getAccuracy());

        if ($this->doMock) {
            $this->assertEquals('IT', $result->getCountry());
            $this->assertEquals(26494, $result->getSamples());
            $this->assertEquals(25, $result->getDurationInMs());
        }
    }

    public function testGetByFirstNameWithLocale(): void
    {
        $genderApiClient = $this->getClient();

        if ($this->doMock) {
            $downloader = $this->createMock(FileGetContents::class);
            $downloader->method('request')
                ->willReturn('{"input":{"first_name":"tanja","locale":"en_GB"},"details":{"credits_used":1,"duration":"23ms","samples":68,"country":"GB"},"result_found":true,"first_name":"Tanja","probability":0.97,"gender":"female"}');
            $genderApiClient->setDownloader($downloader);
        }

        $result = $genderApiClient->getByFirstNameAndLocale('tanja', 'en_GB');
        $this->assertStringContainsStringIgnoringCase('tanja', $result->getFirstName());
        $this->assertEquals('female', $result->getGender());
        $this->assertGreaterThan(90, $result->getAccuracy());
    }

    public function testGetByFirstNameWithInvalidLocale(): void
    {
        $this->expectException(Client\InvalidArgumentException::class);

        $genderApiClient = $this->getClient();
        $genderApiClient->getByFirstNameAndLocale('tanja', 'xxx');
    }

    public function testGetByFirstNameWithIpAddress(): void
    {
        $genderApiClient = $this->getClient();

        if ($this->doMock) {
            $downloader = $this->createMock(FileGetContents::class);
            $downloader->method('request')
                ->willReturn('{"input":{"first_name":"tanja","ip":"178.27.52.144"},"details":{"credits_used":1,"duration":"23ms","samples":68,"country":"DE"},"result_found":true,"first_name":"Tanja","probability":0.98,"gender":"female"}');
            $genderApiClient->setDownloader($downloader);
        }

        $result = $genderApiClient->getByFirstNameAndClientIpAddress('tanja', '178.27.52.144');
        $this->assertStringContainsStringIgnoringCase('tanja', $result->getFirstName());
        $this->assertEquals('female', $result->getGender());
    }

    public function testGetByFirstNameWithCountryName(): void
    {
        $genderApiClient = $this->getClient();

        if ($this->doMock) {
            $downloader = $this->createMock(FileGetContents::class);
            $downloader->method('request')
                ->willReturn('{"input":{"first_name":"markus","country":"DE"},"details":{"credits_used":1,"duration":"25ms","samples":26494,"country":"DE"},"result_found":true,"first_name":"Markus","probability":0.99,"gender":"male"}');
            $genderApiClient->setDownloader($downloader);
        }

        $result = $genderApiClient->getByFirstNameAndCountry('markus', 'germany');
        $this->assertStringContainsStringIgnoringCase('markus', $result->getFirstName());
        $this->assertEquals('male', $result->getGender());
    }

    public function testGetInvalidName(): void
    {
        $this->expectException(Client\InvalidArgumentException::class);

        $genderApiClient = $this->getClient();
        $genderApiClient->getByFirstName('');
    }

    public function testGetInvalidCountryCode(): void
    {
        $this->expectException(Client\InvalidArgumentException::class);

        $genderApiClient = $this->getClient();
        $genderApiClient->getByFirstNameAndCountry('markus', 'XX');
    }

    public function testServerNotReachable(): void
    {
        $this->expectException(Client\Downloader\NetworkErrorException::class);

        $genderApiClient = $this->getClient();
        $genderApiClient->setApiUrl('http://localhost:9999');

        if ($this->doMock) {
            $downloader = $this->createMock(FileGetContents::class);
            $downloader->method('request')
                ->willThrowException(new Client\Downloader\NetworkErrorException('Connection refused'));
            $genderApiClient->setDownloader($downloader);
        }

        $genderApiClient->getByFirstName('markus');
    }
}