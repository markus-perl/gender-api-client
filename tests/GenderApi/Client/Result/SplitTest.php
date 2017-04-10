<?php

namespace GenderApi\Client\Downloader;

use GenderApi\Client\Result\EmailAddress;
use GenderApi\Client\Result\SingleName;
use GenderApi\Client\Result\Split;
use GenderApiTest\TestCase;

/**
 * Class SplitTest
 * @package GenderApi\Client\Downloader
 */
class SplitTest extends TestCase
{

    /**
     *
     */
    public function testParseResponse()
    {
        $response = json_decode('{"last_name":"Wagner","first_name":"Michaela","strict":false,"name":"michaela","country":"DE","gender":"female","samples":7010,"accuracy":68,"duration":"41ms"}');

        $snq = new Split();
        $snq->parseResponse($response);

        $this->assertEquals('DE', $snq->getCountry());
        $this->assertEquals('female', $snq->getGender());
        $this->assertEquals(41, $snq->getDurationInMs());
        $this->assertEquals('michaela', $snq->getName());
        $this->assertEquals(7010, $snq->getSamples());
        $this->assertEquals(68, $snq->getAccuracy());
        $this->assertEquals('Michaela', $snq->getFirstName());
        $this->assertEquals('Wagner', $snq->getLastName());

        $this->assertTrue($snq->genderFound());
    }

    /**
     *
     */
    public function testParseResponseInvalidName()
    {
        $response = json_decode('{"last_name":null,"strict":true,"name":"","gender":"unknown","samples":0,"accuracy":0,"duration":"27ms"}');

        $snq = new Split();
        $snq->parseResponse($response);

        $this->assertNull($snq->getCountry());
        $this->assertEquals('unknown', $snq->getGender());
        $this->assertEquals(27, $snq->getDurationInMs());
        $this->assertEquals('', $snq->getName());
        $this->assertEquals(0, $snq->getSamples());
        $this->assertEquals(0, $snq->getAccuracy());
        $this->assertFalse($snq->genderFound());
    }

}