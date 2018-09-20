<?php

namespace GenderApi\Client;

use GenderApi\Client;
use GenderApi\Client\Downloader\FileGetContents;
use GenderApiTest\TestCase;

/**
 * Class SplitTest
 * @package GenderApi\Client
 */
class SplitTest extends TestCase
{

    /**
     *
     */
    public function testGetByEmailAddressWithoutCountry()
    {
        $genderApiClient = $this->getClient();

        if ($this->doMock) {
            $downloader = $this->createMock(FileGetContents::class);
            $downloader->method('download')
                ->willReturn('{"last_name":"Johnson","first_name":"Robert","strict":false,"name":"robert","gender":"male","samples":145540,"accuracy":99,"duration":"65ms"}');
            $genderApiClient->setDownloader($downloader);
        }

        $result = $genderApiClient->getByFirstNameAndLastName('Robert Johnson');

        $this->assertEquals('robert', $result->getName());
        $this->assertEquals('male', $result->getGender());
        $this->assertEquals(99, $result->getAccuracy());
        $this->assertFalse($result->getStrict());
        $this->assertEquals('Johnson', $result->getLastName());
        $this->assertEquals('Robert', $result->getFirstName());

        if ($this->doMock) {
            $this->assertEquals(145540, $result->getSamples());
            $this->assertEquals(65, $result->getDurationInMs());
        }
    }

    /**
     *
     */
    public function testGetByEmailAddressWithCountry()
    {
        $genderApiClient = $this->getClient();

        if ($this->doMock) {
            $downloader = $this->createMock(FileGetContents::class);
            $downloader->method('download')
                ->willReturn('{"last_name":"Johnson","first_name":"Robert","strict":true,"name":"robert","country":"US","gender":"male","samples":43118,"accuracy":100,"duration":"19ms"}');
            $genderApiClient->setDownloader($downloader);
        }

        $result = $genderApiClient->getByFirstNameAndLastNameAndCountry('Robert Johnson', 'US', true);

        $this->assertEquals('robert', $result->getName());
        $this->assertEquals('male', $result->getGender());
        $this->assertEquals(100, $result->getAccuracy());
        $this->assertTrue($result->getStrict());
        $this->assertEquals('Johnson', $result->getLastName());
        $this->assertEquals('Robert', $result->getFirstName());
        $this->assertEquals('US', $result->getCountry());

        if ($this->doMock) {
            $this->assertEquals(43118, $result->getSamples());
            $this->assertEquals(19, $result->getDurationInMs());
        }
    }

}