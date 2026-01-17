<?php

declare(strict_types=1);

namespace GenderApi\Client;

use GenderApi\Client;
use GenderApi\Client\Downloader\FileGetContents;
use GenderApiTest\TestCase;

/**
 * Tests for Stats endpoint (API v2)
 */
class StatsTest extends TestCase
{
    public function testGetStats(): void
    {
        $genderApiClient = $this->getClient();

        if ($this->doMock) {
            $downloader = $this->createMock(FileGetContents::class);
            $downloader->method('request')
                ->willReturn('{"is_limit_reached":false,"remaining_credits":23456,"details":{"credits_used":0,"duration":"32ms"},"usage_last_month":{"date":"2021-09","credits_used":30446}}');
            $genderApiClient->setDownloader($downloader);
        }

        $result = $genderApiClient->getStats();

        $this->assertNotNull($result->getRemainingCredits());
        $this->assertFalse($result->isLimitReached());

        if ($this->doMock) {
            $this->assertEquals(23456, $result->getRemainingCredits());
            $this->assertEquals(30446, $result->getUsageLastMonth());
            $this->assertEquals(32, $result->getDurationInMs());
        }
    }
}