<?php

declare(strict_types=1);

namespace GenderApiIntegration;

/**
 * Integration tests for full name (split) lookup endpoints
 *
 * @group integration
 */
class FullNameTest extends IntegrationTestCase
{
    public function testGetByFullName(): void
    {
        $result = $this->client->getByFirstNameAndLastName('Thomas Mueller');

        $this->assertEquals('Thomas', $result->getFirstName());
        $this->assertEquals('Mueller', $result->getLastName());
        $this->assertEquals('male', $result->getGender());
        $this->assertGreaterThan(90, $result->getAccuracy());
    }

    public function testGetByFullNameWithCountry(): void
    {
        $result = $this->client->getByFirstNameAndLastNameAndCountry('Andrea Rossi', 'IT');

        $this->assertEquals('Andrea', $result->getFirstName());
        $this->assertEquals('Rossi', $result->getLastName());
        $this->assertEquals('male', $result->getGender()); // male in Italy
    }

    public function testGetByFullNameWithCountryGermany(): void
    {
        $result = $this->client->getByFirstNameAndLastNameAndCountry('Andrea Meyer', 'DE');

        $this->assertEquals('Andrea', $result->getFirstName());
        $this->assertEquals('Meyer', $result->getLastName());
        $this->assertEquals('female', $result->getGender()); // female in Germany
    }

    public function testGenderFound(): void
    {
        $result = $this->client->getByFirstNameAndLastName('Thomas Mueller');
        $this->assertTrue($result->genderFound());
    }
}
