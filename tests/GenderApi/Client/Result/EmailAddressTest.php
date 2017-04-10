<?php

namespace GenderApi\Client\Downloader;

use GenderApi\Client\Result\EmailAddress;
use GenderApiTest\TestCase;

/**
 * Class EmailAddressTest
 * @package GenderApi\Client\Downloader
 */
class EmailAddressTest extends TestCase
{

    /**
     *
     */
    public function testParseResponse()
    {
        $response = json_decode('{"email":"jack.bauer@gmail.com","lastname":"bauer","mailprovider":"gmail","name":"jack","country":"US","gender":"male","samples":10764,"accuracy":99,"duration":"87ms"}');

        $eaq = new EmailAddress();
        $eaq->parseResponse($response);

        $this->assertEquals('US', $eaq->getCountry());
        $this->assertEquals('male', $eaq->getGender());
        $this->assertEquals(87, $eaq->getDurationInMs());
        $this->assertEquals('gmail', $eaq->getMailProvider());
        $this->assertEquals('bauer', $eaq->getLastName());
        $this->assertEquals('jack', $eaq->getName());
        $this->assertEquals(10764, $eaq->getSamples());
        $this->assertEquals(99, $eaq->getAccuracy());
        $this->assertTrue($eaq->genderFound());
    }

    /**
     *
     */
    public function testParseResponseInvalidName()
    {
        $response = json_decode('{"email":"grgrggr3232@gmail.com","lastname":null,"mailprovider":"gmail","name":"grgrggr","country":"US","gender":"unknown","samples":0,"accuracy":0,"duration":"24ms"}');

        $eaq = new EmailAddress();
        $eaq->parseResponse($response);

        $this->assertEquals('US', $eaq->getCountry());
        $this->assertEquals('unknown', $eaq->getGender());
        $this->assertEquals(24, $eaq->getDurationInMs());
        $this->assertEquals('gmail', $eaq->getMailProvider());
        $this->assertEquals(null, $eaq->getLastName());
        $this->assertEquals('grgrggr', $eaq->getName());
        $this->assertEquals(0, $eaq->getSamples());
        $this->assertEquals(0, $eaq->getAccuracy());
        $this->assertFalse($eaq->genderFound());
    }

}