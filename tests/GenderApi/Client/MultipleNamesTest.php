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
        $genderApiClient = new Client($this->apiKey);

        if ($this->doMock) {
            $downloader = $this->createMock(FileGetContents::class);
            $downloader->method('download')
                ->willReturn('{"name":"markus;elisa","result":[{"name":"elisa","gender":"female","samples":32786,"accuracy":98},'
                    . '{"name":"markus","gender":"male","samples":26494,"accuracy":99}],"duration":"38ms"}');
            $genderApiClient->setDownloader($downloader);
        }

        $result = $genderApiClient->getByMultipleNames(array('Markus', 'Elisa'));
        $this->assertEquals(2, count($result));

        foreach ($result as $key => $name) {
            if ($key == 0) {
                $this->assertEquals('elisa', $name->getName());
                $this->assertEquals('female', $name->getGender());

                if ($this->doMock) {
                    $this->assertEquals(32786, $name->getSamples());
                    $this->assertEquals(98, $name->getAccuracy());
                }
            }
            if ($key == 1) {
                $this->assertEquals('markus', $name->getName());
                $this->assertEquals('male', $name->getGender());

                if ($this->doMock) {
                    $this->assertEquals(26494, $name->getSamples());
                    $this->assertEquals(99, $name->getAccuracy());
                }
            }
        }
    }

    /**
     *
     */
    public function testGetByFirstNameWithCountry()
    {
        $genderApiClient = new Client($this->apiKey);

        if ($this->doMock) {
            $downloader = $this->createMock(FileGetContents::class);
            $downloader->method('download')
                ->willReturn('{"name":"markus;elisa","country":"DE","result":[{"name":"elisa","gender":"female","samples":653,"accuracy":97},{"name":"markus","gender":"male","samples":15230,"accuracy":99}],"duration":"37ms"}');
            $genderApiClient->setDownloader($downloader);
        }

        $result = $genderApiClient->getByMultipleNamesAndCountry(array('Markus', 'Elisa'), 'DE');
        $this->assertEquals(2, count($result));

        foreach ($result as $key => $name) {
            if ($key == 0) {
                $this->assertEquals('elisa', $name->getName());
                $this->assertEquals('female', $name->getGender());
                $this->assertEquals('DE', $name->getCountry());

                if ($this->doMock) {
                    $this->assertEquals(653, $name->getSamples());
                    $this->assertEquals(97, $name->getAccuracy());
                }
            }
            if ($key == 1) {
                $this->assertEquals('markus', $name->getName());
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