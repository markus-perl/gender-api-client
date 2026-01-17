<?php

declare(strict_types=1);

namespace GenderApiIntegration;

/**
 * Integration tests for account statistics endpoint
 *
 * @group integration
 */
class StatsTest extends IntegrationTestCase
{
    public function testGetStats(): void
    {
        $result = $this->client->getStats();

        // Stats should return account information
        $this->assertNotNull($result->getRemainingCredits());
        $this->assertIsInt($result->getRemainingCredits());
    }

    public function testGetStatsHasLimitReached(): void
    {
        $result = $this->client->getStats();

        // Should indicate if limit is reached
        $this->assertNotNull($result->isLimitReached());
        $this->assertIsBool($result->isLimitReached());
    }

    public function testGetStatsHasDuration(): void
    {
        $result = $this->client->getStats();

        // Response should include duration
        $this->assertNotNull($result->getDurationInMs());
        $this->assertIsInt($result->getDurationInMs());
        $this->assertGreaterThanOrEqual(0, $result->getDurationInMs());
    }

    public function testGetStatsQueryUrl(): void
    {
        $result = $this->client->getStats();

        // Should have a query URL recorded
        $this->assertNotNull($result->getQueryUrl());
        $this->assertStringContainsString('gender-api.com', $result->getQueryUrl());
    }
}
