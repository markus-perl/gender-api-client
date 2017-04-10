<?php

namespace GenderApi\Client;

use GenderApi\Client;
use GenderApi\Client\Downloader\FileGetContents;
use GenderApiTest\TestCase;

/**
 * Class StatsTest
 * @package GenderApi\Client
 */
class StatsTest extends TestCase
{

    /**
     *
     */
    public function testGetStats()
    {
        $genderApiClient = new Client($this->apiKey);

        if ($this->doMock) {
            $downloader = $this->createMock(FileGetContents::class);
            $downloader->method('download')
                ->willReturn('{"key":"XXXXXXXXXXXXXXXXX","is_limit_reached":false,"remaining_requests":400,"amount_month_start":500,"amount_month_bought":500,"duration":"17ms"}');
            $genderApiClient->setDownloader($downloader);
        }

        $stats = $genderApiClient->getStats();
        $this->assertFalse($stats->isLimitReached());

        if ($this->doMock) {
            $this->assertEquals('XXXXXXXXXXXXXXXXX', $stats->getKey());
            $this->assertEquals(400, $stats->getRemainingRequests());
            $this->assertEquals(500, $stats->getAmountMonthBought());
            $this->assertEquals(17, $stats->getDurationInMs());
        }
    }

}