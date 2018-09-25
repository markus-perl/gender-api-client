<?php

namespace GenderApi\Client;

use GenderApi\Client;
use GenderApi\Client\Downloader\FileGetContents;
use GenderApiTest\TestCase;

/**
 * Class CountryOfOriginTest
 * @package GenderApi\Client
 */
class CountryOfOriginTest extends TestCase
{

    /**
     *
     */
    public function testGetCountryOfOrigin()
    {
        $genderApiClient = $this->getClient();

        if ($this->doMock) {
            $downloader = $this->createMock(FileGetContents::class);
            $downloader->method('download')
                ->willReturn('{
    "name": "markus",
    "country_of_origin": [
        {
            "country_name": "Austria",
            "country": "AT",
            "probability": 0.29,
            "continental_region": "Europe",
            "statistical_region": "Western Europe"
        },
        {
            "country_name": "Germany",
            "country": "DE",
            "probability": 0.15,
            "continental_region": "Europe",
            "statistical_region": "Western Europe"
        },
        {
            "country_name": "Other",
            "country": "--",
            "probability": 0.56
        }
    ],
    "name_sanitized": "Markus",
    "gender": "male",
    "samples": 26494,
    "accuracy": 99,
    "country_of_origin_map_url": "https:\\/\\/beta.gender-api.com\\/en\\/map\\/19\\/35a978bd6265e1a8",
    "credits_used": 2,
    "duration": "82ms"
}');
            $genderApiClient->setDownloader($downloader);
        }

        $result = $genderApiClient->getCountryOfOrigin('markus');
        $this->assertInstanceOf(Client\Result\CountryOfOrigin::class, $result);
        $this->assertEquals('markus', $result->getName());
        $this->assertEquals('male', $result->getGender());
        $this->assertEquals(99, $result->getAccuracy());
        $this->assertContains('https://beta.gender-api.com/en/map/', $result->getCountryOfOriginMapUurl());

        $countryOfOrigin = $result->getCountryOfOrigin();
        $this->assertEquals('AT', $countryOfOrigin[0]->getCountry());
        $this->assertEquals('Austria', $countryOfOrigin[0]->getCountryName());
        $this->assertEquals('Western Europe', $countryOfOrigin[0]->getStatisticalRegion());
        $this->assertEquals('Europe', $countryOfOrigin[0]->getContinentalRegion());

        if ($this->doMock) {
            $this->assertEquals(26494, $result->getSamples());
            $this->assertEquals(82, $result->getDurationInMs());
            $this->assertEquals('https://beta.gender-api.com/en/map/19/35a978bd6265e1a8', $result->getCountryOfOriginMapUurl());
        }
    }

}