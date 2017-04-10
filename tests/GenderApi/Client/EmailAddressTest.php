<?php

namespace GenderApi\Client;

use GenderApi\Client;
use GenderApi\Client\Downloader\FileGetContents;
use GenderApiTest\TestCase;

/**
 * Class EmailAddressTest
 * @package GenderApi\Client
 */
class EmailAddressTest extends TestCase
{

    /**
     *
     */
    public function testGetByEmailAddressWithoutCountry()
    {
        $genderApiClient = new Client($this->apiKey);

        if ($this->doMock) {
            /* @var FileGetContents|\PHPUnit_Framework_MockObject_MockObject $downloader */
            $downloader = $this->createMock(FileGetContents::class);
            $downloader->method('download')
                ->willReturn('{"email":"elisabeth1499@gmail.com","lastname":null,"mailprovider":"gmail","name":"elisabeth","gender":"female","samples":17296,"accuracy":98,"duration":"20ms"}');
            $genderApiClient->setDownloader($downloader);
        }

        $result = $genderApiClient->getByEmailAddress('elisabeth1499@gmail.com');

        $this->assertEquals('elisabeth', $result->getName());
        $this->assertEquals('female', $result->getGender());
        $this->assertEquals(98, $result->getAccuracy());
        $this->assertEquals('elisabeth1499@gmail.com', $result->getEmailAddress());
        $this->assertEquals(null, $result->getLastName());
        $this->assertEquals('gmail', $result->getMailProvider());

        if ($this->doMock) {
            $this->assertEquals(17296, $result->getSamples());
            $this->assertEquals(20, $result->getDurationInMs());
        }
    }

    /**
     *
     */
    public function testGetByEmailAddressWithCountry()
    {
        $genderApiClient = new Client($this->apiKey);

        if ($this->doMock) {
            $downloader = $this->createMock(FileGetContents::class);
            $downloader->method('download')
                ->willReturn('{"email":"elisabeth1499@gmail.com","lastname":null,"mailprovider":"gmail","name":"elisabeth","country":"GB","gender":"female","samples":174,"accuracy":97,"duration":"12ms"}');
            $genderApiClient->setDownloader($downloader);
        }

        $result = $genderApiClient->getByEmailAddressAndCountry('elisabeth1499@gmail.com', 'GB');

        $this->assertEquals('elisabeth', $result->getName());
        $this->assertEquals('female', $result->getGender());
        $this->assertEquals(97, $result->getAccuracy());
        $this->assertEquals('elisabeth1499@gmail.com', $result->getEmailAddress());
        $this->assertEquals(null, $result->getLastName());
        $this->assertEquals('gmail', $result->getMailProvider());
        $this->assertEquals('GB', $result->getCountry());

        if ($this->doMock) {
            $this->assertEquals(174, $result->getSamples());
            $this->assertEquals(12, $result->getDurationInMs());
        }
    }

}