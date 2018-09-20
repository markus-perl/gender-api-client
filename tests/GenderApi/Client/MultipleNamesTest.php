<?php

namespace GenderApi\Client;

use GenderApi\Client;
use GenderApi\Client\Downloader\FileGetContents;
use GenderApiTest\TestCase;

/**
 * Class MultipleNamesTest
 * @package GenderApi\Client
 */
class MultipleNamesTest extends TestCase
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
                ->willReturn('{"name":"markus;elisa","result":[{"name":"Elisa","gender":"female","samples":32786,"accuracy":98},'
                    . '{"name":"Markus","gender":"male","samples":26494,"accuracy":99}],"duration":"38ms"}');
            $genderApiClient->setDownloader($downloader);
        }

        $result = $genderApiClient->getByMultipleNames(array('Markus', 'Elisa'));
        $this->assertEquals(2, count($result));
        $matches = 0;


        foreach ($result as $key => $name) {

            if ($name->getName() == 'Elisa') {
                $this->assertEquals('female', $name->getGender());
                $matches++;

                if ($this->doMock) {
                    $this->assertEquals(32786, $name->getSamples());
                    $this->assertEquals(98, $name->getAccuracy());
                }
            }
            if ($name->getName() == 'Markus') {
                $this->assertEquals('male', $name->getGender());
                $matches++;

                if ($this->doMock) {
                    $this->assertEquals(26494, $name->getSamples());
                    $this->assertEquals(99, $name->getAccuracy());
                }
            }
        }

        if ($matches !== 2) {
            $this->fail('names not found: ' . print_r($result, true));
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
                ->willReturn('{"name":"markus;elisa","country":"DE","result":[{"name":"elisa","gender":"female","samples":653,"accuracy":97},{"name":"markus","gender":"male","samples":15230,"accuracy":99}],"duration":"37ms"}');
            $genderApiClient->setDownloader($downloader);
        }

        $result = $genderApiClient->getByMultipleNamesAndCountry(array('Markus', 'Elisa'), 'DE');
        $this->assertEquals(2, count($result));

        foreach ($result as $key => $name) {
            if ($name->getName() == 'elisa') {
                $this->assertEquals('female', $name->getGender());
                $this->assertEquals('DE', $name->getCountry());

                if ($this->doMock) {
                    $this->assertEquals(653, $name->getSamples());
                    $this->assertEquals(97, $name->getAccuracy());
                }
            }
            if ($name->getName() == 'markus') {
                $this->assertEquals('male', $name->getGender());
                $this->assertEquals('DE', $name->getCountry());

                if ($this->doMock) {
                    $this->assertEquals(15230, $name->getSamples());
                    $this->assertEquals(99, $name->getAccuracy());
                }
            }
        }
    }

}