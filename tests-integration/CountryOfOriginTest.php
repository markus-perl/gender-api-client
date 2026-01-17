<?php

declare(strict_types=1);

namespace GenderApiIntegration;

/**
 * Integration tests for country of origin lookup endpoints
 *
 * @group integration
 */
class CountryOfOriginTest extends IntegrationTestCase
{
    public function testGetCountryOfOrigin(): void
    {
        $result = $this->client->getCountryOfOrigin('Madita');

        $this->assertEquals('Madita', $result->getFirstName());
        $this->assertEquals('female', $result->getGender());
        $this->assertNotNull($result->getCountryOfOriginMapUrl());

        $countries = $result->getCountryOfOrigin();

        // Madita is typically German
        $foundGermany = false;
        foreach ($countries as $country) {
            if ($country->getCountry() === 'DE') {
                $foundGermany = true;
                break;
            }
        }

        $this->assertTrue($foundGermany, 'Should contain DE (Germany) as a country of origin for Madita');
    }
}
