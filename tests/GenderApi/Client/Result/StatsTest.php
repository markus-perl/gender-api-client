<?php

namespace GenderApi\Client\Downloader;

use GenderApi\Client\Result\EmailAddress;
use GenderApi\Client\Result\SingleName;
use GenderApi\Client\Result\Split;
use GenderApi\Client\Result\Stats;
use GenderApiTest\TestCase;

/**
 * Class StatsTest
 * @package GenderApi\Client\Downloader
 */
class StatsTest extends TestCase
{

    /**
     *
     */
    public function testParseResponse()
    {
        $response = json_decode('{"key":"XXXXXXXXXXXXX","is_limit_reached":false,"remaining_requests":2323,"amount_month_start":2332,"amount_month_bought":0,"duration":"19ms"}');

        $sq = new Stats();
        $sq->parseResponse($response);

        $this->assertEquals('XXXXXXXXXXXXX', $sq->getKey());
        $this->assertFalse( $sq->isLimitReached());
        $this->assertEquals(2323, $sq->getRemainingRequests());
        $this->assertEquals(2332, $sq->getAmountMonthStart());
        $this->assertEquals(0, $sq->getAmountMonthBought());
        $this->assertEquals(19, $sq->getDurationInMs());
    }

}