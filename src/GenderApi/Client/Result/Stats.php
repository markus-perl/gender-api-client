<?php

declare(strict_types=1);

namespace GenderApi\Client\Result;

/**
 * Result for API stats/usage lookup (API v2)
 */
class Stats extends AbstractResult
{
    protected ?bool $isLimitReached = null;

    protected ?int $remainingCredits = null;

    protected ?int $usageLastMonth = null;

    public function isLimitReached(): ?bool
    {
        return $this->isLimitReached;
    }

    /**
     * Get remaining API credits
     */
    public function getRemainingCredits(): ?int
    {
        return $this->remainingCredits;
    }

    /**
     * @deprecated Use getRemainingCredits() instead
     */
    public function getRemainingRequests(): ?int
    {
        return $this->remainingCredits;
    }

    public function getUsageLastMonth(): ?int
    {
        return $this->usageLastMonth;
    }

    public function parseResponse(\stdClass $response): void
    {
        $this->parseV2Details($response);

        if (isset($response->is_limit_reached)) {
            $this->isLimitReached = (bool) $response->is_limit_reached;
        }

        if (isset($response->remaining_credits)) {
            $this->remainingCredits = (int) $response->remaining_credits;
        }

        if (isset($response->usage_last_month) && isset($response->usage_last_month->credits_used)) {
            $this->usageLastMonth = (int) $response->usage_last_month->credits_used;
        }
    }
}