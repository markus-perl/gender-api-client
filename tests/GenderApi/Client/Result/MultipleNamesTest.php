<?php

declare(strict_types=1);

namespace GenderApi\Client\Result;

use GenderApiTest\TestCase;

/**
 * Tests for MultipleNames result parser (API v2)
 */
class MultipleNamesTest extends TestCase
{
    public function testParseResponse(): void
    {
        $result = new MultipleNames();
        $result->parseResponse((object) [
            'results' => [
                (object) [
                    'input' => (object) ['first_name' => 'stefan', 'id' => '1'],
                    'details' => (object) ['credits_used' => 1, 'duration' => '25ms', 'samples' => 26494],
                    'result_found' => true,
                    'first_name' => 'Stefan',
                    'probability' => 0.99,
                    'gender' => 'male',
                ],
                (object) [
                    'input' => (object) ['first_name' => 'elisabeth', 'id' => '2'],
                    'details' => (object) ['credits_used' => 1, 'duration' => '25ms', 'samples' => 17000],
                    'result_found' => true,
                    'first_name' => 'Elisabeth',
                    'probability' => 0.99,
                    'gender' => 'female',
                ],
            ],
        ]);

        $this->assertCount(2, $result);

        $names = [];
        foreach ($result as $singleResult) {
            $names[$singleResult->getFirstName()] = $singleResult->getGender();
        }

        $this->assertEquals('male', $names['Stefan']);
        $this->assertEquals('female', $names['Elisabeth']);
    }
}