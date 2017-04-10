<?php

namespace GenderApi\Client\Downloader;

use GenderApi\Client\Result\MultipleNames;
use GenderApiTest\TestCase;

/**
 * Class EmailAddressTest
 * @package GenderApi\Client\Downloader
 */
class MultipleNamesTest extends TestCase
{

    /**
     *
     */
    public function testParseResponse()
    {
        $response = json_decode('{"name":"jenniffer;bibi","result":[{"name":"bibi","gender":"female","samples":6715,"accuracy":62},{"name":"jenniffer","gender":"female","samples":793,"accuracy":99}],"duration":"12ms"}');

        $mnq = new MultipleNames();
        $mnq->parseResponse($response);

        foreach ($mnq as $key => $name) {

            if ($key == 0) {
                $this->assertEquals('bibi', $name->getName());
                $this->assertEquals(6715, $name->getSamples());
                $this->assertEquals(62, $name->getAccuracy());
            }

            if ($key == 1) {
                $this->assertEquals('jenniffer', $name->getName());
                $this->assertEquals(793, $name->getSamples());
                $this->assertEquals(99, $name->getAccuracy());
            }

            $this->assertEquals('female', $name->getGender());

        }
    }

}