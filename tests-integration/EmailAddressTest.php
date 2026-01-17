<?php

declare(strict_types=1);

namespace GenderApiIntegration;

/**
 * Integration tests for email address gender lookup endpoints
 *
 * @group integration
 */
class EmailAddressTest extends IntegrationTestCase
{
    public function testGetByEmailAddress(): void
    {
        $result = $this->client->getByEmailAddress('sandra.miller@gmail.com');

        $this->assertEquals('Sandra', $result->getFirstName());
        $this->assertEquals('female', $result->getGender());
        $this->assertEquals('sandra.miller@gmail.com', $result->getEmailAddress());
    }

    public function testGetByEmailAddressMale(): void
    {
        $result = $this->client->getByEmailAddress('john.smith@example.com');

        $this->assertEquals('John', $result->getFirstName());
        $this->assertEquals('male', $result->getGender());
    }

    public function testGetByEmailAddressAndCountry(): void
    {
        $result = $this->client->getByEmailAddressAndCountry('maria.garcia@hotmail.com', 'ES');

        $this->assertEquals('Maria', $result->getFirstName());
        $this->assertEquals('female', $result->getGender());
    }

    public function testGetByEmailAddressWithNumbers(): void
    {
        $result = $this->client->getByEmailAddress('michael.johnson123@company.org');

        $this->assertEquals('Michael', $result->getFirstName());
        $this->assertEquals('male', $result->getGender());
    }

    public function testGetByEmailAddressFirstNameOnly(): void
    {
        $result = $this->client->getByEmailAddress('anna@example.com');

        $this->assertEquals('female', $result->getGender());
    }
}
