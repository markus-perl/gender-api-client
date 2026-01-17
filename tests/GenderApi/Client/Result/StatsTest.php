<?php

declare(strict_types=1);

namespace GenderApi\Client\Result;

use GenderApiTest\TestCase;

/**
 * Tests for Stats result parser (API v2)
 */
class StatsTest extends TestCase
{
    public function testParseResponse(): void
    {
        $result = new Stats();
        $result->parseResponse((object) [
            'is_limit_reached' => false,
            'remaining_credits' => 23456,
            'details' => (object) ['credits_used' => 0, 'duration' => '32ms'],
            'usage_last_month' => (object) ['date' => '2021-09', 'credits_used' => 30446],
        ]);

        $this->assertFalse($result->isLimitReached());
        $this->assertEquals(23456, $result->getRemainingCredits());
        $this->assertEquals(30446, $result->getUsageLastMonth());
        $this->assertEquals(32, $result->getDurationInMs());
    }
}