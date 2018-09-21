<?php

namespace GenderApi\Client;

use GenderApi\Client;
use GenderApi\Client\Downloader\FileGetContents;
use GenderApiTest\TestCase;

/**
 * Class SingleNameTest
 * @package GenderApi\Client
 */
class SingleNameTest extends TestCase
{

    /**
     *
     */
    public function testGetByFirstNameWithoutCountry()
    {
        $genderApiClient = $this->getClient();

        if ($this->doMock) {
            $downloader = $this->createMock(FileGetContents::class);
            $downloader->method('download')
                ->willReturn('{"name":"markus","gender":"male","samples":26494,"accuracy":99,"duration":"25ms"}');
            $genderApiClient->setDownloader($downloader);
        }

        $result = $genderApiClient->getByFirstName('markus');
        $this->assertEquals('markus', $result->getName());
        $this->assertEquals('male', $result->getGender());
        $this->assertEquals(99, $result->getAccuracy());

        if ($this->doMock) {
            $this->assertEquals(26494, $result->getSamples());
            $this->assertEquals(25, $result->getDurationInMs());
        }
    }

    /**
     *
     */
    public function testGetByFirstNameWithCountry()
    {
        $genderApiClient = $this->getClient();

        if ($this->doMock) {
            $downloader = $this->createMock(FileGetContents::class);
            $downloader->method('download')
                ->willReturn('{"name":"tanja","gender":"female","samples":26494,"accuracy":98,"duration":"25ms","country":"IT"}');
            $genderApiClient->setDownloader($downloader);
        }

        $result = $genderApiClient->getByFirstNameAndCountry('tanja', 'IT');
        $this->assertEquals('tanja', $result->getName());
        $this->assertEquals('female', $result->getGender());
        $this->assertEquals(98, $result->getAccuracy());
        $this->assertEquals('IT', $result->getCountry());

        if ($this->doMock) {
            $this->assertEquals(26494, $result->getSamples());
            $this->assertEquals(25, $result->getDurationInMs());
        }
    }

    /**
     *
     */
    public function testGetByFirstNameWithLocale()
    {
        $genderApiClient = $this->getClient();

        if ($this->doMock) {
            $downloader = $this->createMock(FileGetContents::class);
            $downloader->method('download')
                ->willReturn('{"name":"tanja","country":"GB","gender":"female","samples":68,"accuracy":97,"duration":"23ms"}');
            $genderApiClient->setDownloader($downloader);
        }

        $result = $genderApiClient->getByFirstNameAndLocale('tanja', 'en_GB');

        $this->assertEquals('tanja', $result->getName());
        $this->assertEquals('female', $result->getGender());
        $this->assertEquals(97, $result->getAccuracy());
        $this->assertEquals('GB', $result->getCountry());

        if ($this->doMock) {
            $this->assertEquals(68, $result->getSamples());
            $this->assertEquals(23, $result->getDurationInMs());
        }
    }

    /**
     * @expectedException \GenderApi\Client\InvalidArgumentException
     */
    public function testGetByFirstNameWithInvalidLocale()
    {
        $genderApiClient = $this->getClient();

        if ($this->doMock) {
            $downloader = $this->createMock(FileGetContents::class);
            $downloader->method('download')
                ->willReturn('{"name":"tanja","country":"GB","gender":"female","samples":68,"accuracy":97,"duration":"23ms"}');
            $genderApiClient->setDownloader($downloader);
        }

        $genderApiClient->getByFirstNameAndLocale('tanja', 'de_XZ');
    }

    /**
     *
     */
    public function testGetByFirstNameWithIpAddress()
    {
        $genderApiClient = $this->getClient();

        if ($this->doMock) {
            $downloader = $this->createMock(FileGetContents::class);
            $downloader->method('download')
                ->willReturn('{"name":"tanja","country":"DE","gender":"female","samples":7261,"accuracy":98,"duration":"26ms"}');
            $genderApiClient->setDownloader($downloader);
        }

        $result = $genderApiClient->getByFirstNameAndClientIpAddress('tanja', '178.27.66.144');

        $this->assertEquals('tanja', $result->getName());
        $this->assertEquals('female', $result->getGender());
        $this->assertEquals(98, $result->getAccuracy());
        $this->assertEquals('DE', $result->getCountry());

        if ($this->doMock) {
            $this->assertEquals(7261, $result->getSamples());
            $this->assertEquals(26, $result->getDurationInMs());
        }
    }

    /**
     *
     */
    public function testGetByFirstNameWithCountryName()
    {
        $genderApiClient = $this->getClient();

        if ($this->doMock) {
            $downloader = $this->createMock(FileGetContents::class);
            $downloader->method('download')
                ->willReturn('{"name":"tanja","gender":"female","samples":26494,"accuracy":98,"duration":"25ms","country":"DE"}');
            $genderApiClient->setDownloader($downloader);
        }

        $result = $genderApiClient->getByFirstNameAndCountry('tanja', 'Germany');
        $this->assertEquals('tanja', $result->getName());
        $this->assertEquals('female', $result->getGender());
        $this->assertEquals('98', $result->getAccuracy());
        $this->assertEquals('DE', $result->getCountry());

        if ($this->doMock) {
            $this->assertEquals(26494, $result->getSamples());
            $this->assertEquals(25, $result->getDurationInMs());
        }
    }

    /**
     *
     */
    public function testGetInvalidName()
    {
        $genderApiClient = $this->getClient();

        if ($this->doMock) {
            $downloader = $this->createMock(FileGetContents::class);
            $downloader->method('download')
                ->willReturn('{"name":"alksdjfkla","country":"IT","gender":"unknown","samples":0,"accuracy":0,"duration":"24ms"}');
            $genderApiClient->setDownloader($downloader);
        }

        $result = $genderApiClient->getByFirstName('alksdjfkla');
        $this->assertEquals('alksdjfkla', $result->getName());
        $this->assertEquals('unknown', $result->getGender());
        $this->assertEquals(0, $result->getAccuracy());
        $this->assertEquals(0, $result->getSamples());

        if ($this->doMock) {
            $this->assertEquals(24, $result->getDurationInMs());
        }
    }

    /**
     * @expectedException \GenderApi\Client\InvalidArgumentException
     * @expectedExceptionMessage Invalid country code. Please provide a valid country code or country name. See https://gender-api.com/en/api-docs/localization.
     */
    public function testGetInvalidCountryCode()
    {
        $genderApiClient = $this->getClient();

        if ($this->doMock) {
            $downloader = $this->createMock(FileGetContents::class);
            $downloader->method('download')
                ->willReturn('{"name":"markus","errno":10,"errmsg":"invalid country code","gender":"unknown","samples":0,"accuracy":0,"duration":"33ms"}');
            $genderApiClient->setDownloader($downloader);
        }

        $result = $genderApiClient->getByFirstNameAndCountry('markus', 'XZ');
        $this->assertEquals('markus', $result->getName());
        $this->assertEquals('unknown', $result->getGender());
        $this->assertEquals(0, $result->getAccuracy());
        $this->assertEquals(0, $result->getSamples());

        if ($this->doMock) {
            $this->assertEquals(24, $result->getDurationInMs());
        }
    }

    /**
     * @expectedException \GenderApi\Client\Downloader\NetworkErrorException
     */
    public function testServerNotReachable()
    {
        $genderApiClient = $this->getClient();

        if ($this->doMock) {
            $downloader = $this->createMock(FileGetContents::class);
            $downloader->method('download')
                ->willThrowException(new \GenderApi\Client\Downloader\NetworkErrorException('file_get_contents(https://gender-api.com/wrong-url?key=XXXXXXXXXXXXX&name=markus): failed to open stream: HTTP request failed! HTTP/1.1 404 Not Found'));
            $genderApiClient->setDownloader($downloader);
        }

        $genderApiClient->setApiUrl('https://gender-api.com/wrong-url?');
        $genderApiClient->getByFirstName('tanja', 'IT');
    }
}