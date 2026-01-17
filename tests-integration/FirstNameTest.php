<?php

declare(strict_types=1);

namespace GenderApiIntegration;

/**
 * Integration tests for first name gender lookup endpoints
 *
 * @group integration
 */
class FirstNameTest extends IntegrationTestCase
{
    public function testGetByFirstName(): void
    {
        $result = $this->client->getByFirstName('Markus');

        $this->assertEquals('Markus', $result->getFirstName());
        $this->assertEquals('male', $result->getGender());
        $this->assertGreaterThan(90, $result->getAccuracy());
        $this->assertGreaterThan(500, $result->getSamples());
    }

    public function testGetByFirstNameAndCountry(): void
    {
        $result = $this->client->getByFirstNameAndCountry('Andrea', 'IT');

        // Andrea is typically male in Italy
        $this->assertEquals('Andrea', $result->getFirstName());
        $this->assertEquals('male', $result->getGender());
        $this->assertEquals('IT', $result->getCountry());
        $this->assertGreaterThan(90, $result->getAccuracy());
    }

    public function testGetByFirstNameAndCountryGermany(): void
    {
        $result = $this->client->getByFirstNameAndCountry('Andrea', 'DE');

        // Andrea is typically female in Germany
        $this->assertEquals('Andrea', $result->getFirstName());
        $this->assertEquals('female', $result->getGender());
        $this->assertEquals('DE', $result->getCountry());
        $this->assertGreaterThan(80, $result->getAccuracy());
    }

    public function testGetByFirstNameAndLocale(): void
    {
        $result = $this->client->getByFirstNameAndLocale('Andrea', 'it_IT');

        $this->assertEquals('Andrea', $result->getFirstName());
        $this->assertEquals('male', $result->getGender());
    }

    public function testGetByFirstNameAndIpAddress(): void
    {
        // IP for Italy
        $result = $this->client->getByFirstNameAndClientIpAddress('Andrea', '151.0.0.1');

        $this->assertEquals('Andrea', $result->getFirstName());
        $this->assertEquals('male', $result->getGender());
    }
}
