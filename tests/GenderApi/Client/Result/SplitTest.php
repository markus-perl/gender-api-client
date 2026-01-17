<?php

declare(strict_types=1);

namespace GenderApi\Client\Result;

use GenderApiTest\TestCase;

/**
 * Tests for Split result parser (API v2)
 */
class SplitTest extends TestCase
{
    public function testParseResponse(): void
    {
        $result = new Split();
        $result->parseResponse((object) [
            'input' => (object) ['full_name' => 'Frank Underwood'],
            'details' => (object) ['credits_used' => 1, 'duration' => '15ms', 'samples' => 5000],
            'result_found' => true,
            'first_name' => 'Frank',
            'last_name' => 'Underwood',
            'probability' => 0.99,
            'gender' => 'male',
        ]);

        $this->assertEquals('Frank', $result->getFirstName());
        $this->assertEquals('Underwood', $result->getLastName());
        $this->assertEquals('male', $result->getGender());
        $this->assertEquals(0.99, $result->getProbability());
        $this->assertTrue($result->genderFound());
    }
}