<?php

declare(strict_types=1);

namespace GenderApi\Client;

use GenderApi\Client;
use GenderApi\Client\Downloader\FileGetContents;
use GenderApiTest\TestCase;

/**
 * Tests for Split (full name) lookup (API v2)
 */
class SplitTest extends TestCase
{
    public function testGetByFullNameWithoutCountry(): void
    {
        $genderApiClient = $this->getClient();

        if ($this->doMock) {
            $downloader = $this->createMock(FileGetContents::class);
            $downloader->method('request')
                ->willReturn('{"input":{"full_name":"Frank Underwood"},"details":{"credits_used":1,"duration":"15ms","samples":5000},"result_found":true,"first_name":"Frank","last_name":"Underwood","probability":0.99,"gender":"male"}');
            $genderApiClient->setDownloader($downloader);
        }

        $result = $genderApiClient->getByFirstNameAndLastName('Frank Underwood');

        $this->assertStringContainsStringIgnoringCase('frank', $result->getFirstName());
        $this->assertEquals('male', $result->getGender());
        $this->assertTrue($result->genderFound());

        if ($this->doMock) {
            $this->assertStringContainsStringIgnoringCase('underwood', $result->getLastName());
            $this->assertEquals(5000, $result->getSamples());
        }
    }

    public function testGetByFullNameWithCountry(): void
    {
        $genderApiClient = $this->getClient();

        if ($this->doMock) {
            $downloader = $this->createMock(FileGetContents::class);
            $downloader->method('request')
                ->willReturn('{"input":{"full_name":"Maria Garcia","country":"ES"},"details":{"credits_used":1,"duration":"12ms","samples":1000,"country":"ES"},"result_found":true,"first_name":"Maria","last_name":"Garcia","probability":0.99,"gender":"female"}');
            $genderApiClient->setDownloader($downloader);
        }

        $result = $genderApiClient->getByFirstNameAndLastNameAndCountry('Maria Garcia', 'ES');

        $this->assertStringContainsStringIgnoringCase('maria', $result->getFirstName());
        $this->assertEquals('female', $result->getGender());
    }
}