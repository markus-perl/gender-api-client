<?php

namespace GenderApi\Client\Downloader;

use GenderApi\Client\Result\SingleName;
use GenderApiTest\TestCase;

/**
 * Class EmailAddressTest
 * @package GenderApi\Client\Downloader
 */
class SingleNameTest extends TestCase
{

    /**
     *
     */
    public function testParseResponse()
    {
        $response = json_decode('{"name":"tiffany","country":"IT","gender":"female","samples":191,"accuracy":98,"duration":"27ms"}');

        $snq = new SingleName();
        $snq->parseResponse($response);

        $this->assertEquals('IT', $snq->getCountry());
        $this->assertEquals('female', $snq->getGender());
        $this->assertEquals(27, $snq->getDurationInMs());
        $this->assertEquals('tiffany', $snq->getName());
        $this->assertEquals(191, $snq->getSamples());
        $this->assertEquals(98, $snq->getAccuracy());
        $this->assertTrue($snq->genderFound());
    }

    /**
     *
     */
    public function testParseResponseInvalidName()
    {
        $response = json_decode('{"name":"greeeeeee","gender":"unknown","samples":0,"accuracy":0,"duration":"30ms"}');

        $snq = new SingleName();
        $snq->parseResponse($response);

        $this->assertNull($snq->getCountry());
        $this->assertEquals('unknown', $snq->getGender());
        $this->assertEquals(30, $snq->getDurationInMs());
        $this->assertEquals('greeeeeee', $snq->getName());
        $this->assertEquals(0, $snq->getSamples());
        $this->assertEquals(0, $snq->getAccuracy());
        $this->assertFalse($snq->genderFound());
    }

}