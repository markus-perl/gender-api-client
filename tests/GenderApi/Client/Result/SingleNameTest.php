<?php

declare(strict_types=1);

namespace GenderApi\Client\Result;

use GenderApiTest\TestCase;

/**
 * Tests for SingleName result parser (API v2)
 */
class SingleNameTest extends TestCase
{
    public function testParseResponse(): void
    {
        $result = new SingleName();
        $result->parseResponse((object) [
            'input' => (object) ['first_name' => 'markus'],
            'details' => (object) [
                'credits_used' => 1,
                'duration' => '25ms',
                'samples' => 26494,
                'country' => 'DE',
            ],
            'result_found' => true,
            'first_name' => 'Markus',
            'probability' => 0.99,
            'gender' => 'male',
        ]);

        $this->assertEquals('Markus', $result->getFirstName());
        $this->assertEquals('male', $result->getGender());
        $this->assertEquals(0.99, $result->getProbability());
        $this->assertEquals(99, $result->getAccuracy());
        $this->assertEquals(26494, $result->getSamples());
        $this->assertEquals('DE', $result->getCountry());
        $this->assertEquals(25, $result->getDurationInMs());
        $this->assertEquals(1, $result->getCreditsUsed());
        $this->assertTrue($result->isResultFound());
        $this->assertTrue($result->genderFound());
    }

    public function testParseResponseUnknownGender(): void
    {
        $result = new SingleName();
        $result->parseResponse((object) [
            'input' => (object) ['first_name' => 'xyz'],
            'details' => (object) ['credits_used' => 1, 'duration' => '5ms', 'samples' => 0],
            'result_found' => false,
            'first_name' => null,
            'probability' => null,
            'gender' => 'unknown',
        ]);

        $this->assertEquals('unknown', $result->getGender());
        $this->assertFalse($result->isResultFound());
        $this->assertFalse($result->genderFound());
    }
}