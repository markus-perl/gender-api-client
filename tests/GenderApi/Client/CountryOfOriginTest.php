<?php

declare(strict_types=1);

namespace GenderApi\Client;

use GenderApi\Client;
use GenderApi\Client\Downloader\FileGetContents;
use GenderApiTest\TestCase;

/**
 * Tests for Country of Origin lookup (API v2)
 */
class CountryOfOriginTest extends TestCase
{
    public function testGetCountryOfOrigin(): void
    {
        $genderApiClient = $this->getClient();

        if ($this->doMock) {
            $downloader = $this->createMock(FileGetContents::class);
            $downloader->method('request')
                ->willReturn('{
    "input": {"first_name": "markus"},
    "details": {"credits_used": 2, "duration": "82ms", "samples": 26494},
    "result_found": true,
    "first_name": "Markus",
    "probability": 0.99,
    "gender": "male",
    "country_of_origin": [
        {"country_name": "Germany", "country": "DE", "probability": 0.29, "continental_region": "Europe", "statistical_region": "Western Europe"},
        {"country_name": "Austria", "country": "AT", "probability": 0.15, "continental_region": "Europe", "statistical_region": "Western Europe"}
    ],
    "country_of_origin_map_url": "https://gender-api.com/en/map/19/35a978bd6265e1a8"
}');
            $genderApiClient->setDownloader($downloader);
        }

        $result = $genderApiClient->getCountryOfOrigin('markus');
        $this->assertInstanceOf(Client\Result\CountryOfOrigin::class, $result);
        $this->assertStringContainsStringIgnoringCase('markus', $result->getFirstName());
        $this->assertEquals('male', $result->getGender());
        $this->assertGreaterThan(90, $result->getAccuracy());
        $this->assertStringContainsString('/map/', $result->getCountryOfOriginMapUrl());

        $countryOfOrigin = $result->getCountryOfOrigin();
        $this->assertNotEmpty($countryOfOrigin);
        $this->assertNotNull($countryOfOrigin[0]->getCountry());
        $this->assertNotNull($countryOfOrigin[0]->getCountryName());

        if ($this->doMock) {
            $this->assertEquals('DE', $countryOfOrigin[0]->getCountry());
            $this->assertEquals('Germany', $countryOfOrigin[0]->getCountryName());
            $this->assertEquals('Western Europe', $countryOfOrigin[0]->getStatisticalRegion());
            $this->assertEquals('Europe', $countryOfOrigin[0]->getContinentalRegion());
            $this->assertEquals(26494, $result->getSamples());
            $this->assertEquals(82, $result->getDurationInMs());
        }
    }
}