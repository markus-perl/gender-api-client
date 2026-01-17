<?php

declare(strict_types=1);

namespace GenderApi\Client;

use GenderApi\Client;
use GenderApi\Client\Downloader\FileGetContents;
use GenderApiTest\TestCase;

/**
 * Tests for Email Address lookup (API v2)
 */
class EmailAddressTest extends TestCase
{
    public function testGetByEmailAddressWithoutCountry(): void
    {
        $genderApiClient = $this->getClient();

        if ($this->doMock) {
            $downloader = $this->createMock(FileGetContents::class);
            $downloader->method('request')
                ->willReturn('{"input":{"email":"elisabeth1499@gmail.com"},"details":{"credits_used":1,"duration":"20ms","samples":17296},"result_found":true,"first_name":"Elisabeth","last_name":null,"probability":0.99,"gender":"female"}');
            $genderApiClient->setDownloader($downloader);
        }

        $result = $genderApiClient->getByEmailAddress('elisabeth1499@gmail.com');

        $this->assertStringContainsStringIgnoringCase('elisabeth', $result->getFirstName());
        $this->assertEquals('female', $result->getGender());
        $this->assertGreaterThan(90, $result->getAccuracy());

        if ($this->doMock) {
            $this->assertEquals('elisabeth1499@gmail.com', $result->getEmailAddress());
            $this->assertEquals(17296, $result->getSamples());
            $this->assertEquals(20, $result->getDurationInMs());
        }
    }

    public function testGetByEmailAddressWithCountry(): void
    {
        $genderApiClient = $this->getClient();

        if ($this->doMock) {
            $downloader = $this->createMock(FileGetContents::class);
            $downloader->method('request')
                ->willReturn('{"input":{"email":"elisabeth1499@gmail.com","country":"GB"},"details":{"credits_used":1,"duration":"12ms","samples":174,"country":"GB"},"result_found":true,"first_name":"Elisabeth","last_name":null,"probability":0.97,"gender":"female"}');
            $genderApiClient->setDownloader($downloader);
        }

        $result = $genderApiClient->getByEmailAddressAndCountry('elisabeth1499@gmail.com', 'GB');

        $this->assertStringContainsStringIgnoringCase('elisabeth', $result->getFirstName());
        $this->assertEquals('female', $result->getGender());
        $this->assertGreaterThan(90, $result->getAccuracy());

        if ($this->doMock) {
            $this->assertEquals(174, $result->getSamples());
            $this->assertEquals(12, $result->getDurationInMs());
        }
    }
}