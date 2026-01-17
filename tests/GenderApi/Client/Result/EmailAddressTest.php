<?php

declare(strict_types=1);

namespace GenderApi\Client\Result;

use GenderApiTest\TestCase;

/**
 * Tests for EmailAddress result parser (API v2)
 */
class EmailAddressTest extends TestCase
{
    public function testParseResponse(): void
    {
        $result = new EmailAddress();
        $result->parseResponse((object) [
            'input' => (object) ['email' => 'sandra.miller@gmail.com'],
            'details' => (object) ['credits_used' => 1, 'duration' => '20ms', 'samples' => 5000],
            'result_found' => true,
            'first_name' => 'Sandra',
            'last_name' => 'Miller',
            'probability' => 0.98,
            'gender' => 'female',
        ]);

        $this->assertEquals('Sandra', $result->getFirstName());
        $this->assertEquals('Miller', $result->getLastName());
        $this->assertEquals('sandra.miller@gmail.com', $result->getEmailAddress());
        $this->assertEquals('female', $result->getGender());
        $this->assertEquals(0.98, $result->getProbability());
        $this->assertTrue($result->genderFound());
    }
}