<?php

namespace GenderApi\Client\Result;

/**
 * Class Stats
 * @package GenderApi\Client\Result
 */
class Stats extends AbstractResult
{

    /**
     * Your private server key
     *
     * @var null|string
     */
    protected $key = null;

    /**
     * Returns true if there are no more requests left
     *
     * @var null|bool
     */
    protected $isLimitReached = null;

    /**
     * Count remaining requests
     *
     * @var null|int
     */
    protected $remainingRequests = null;

    /**
     * Requests left at the beginning of the month
     *
     * @var null|int
     */
    protected $amountMonthStart = null;

    /**
     * Requests bought this month
     *
     * @var null|int
     */
    protected $amountMonthBought = null;

    /**
     * Time that took the server to process the request.
     *
     * @var null|int
     */
    protected $durationInMs = null;

    /**
     * Your private server key
     *
     * @return null|string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Returns true if there are no more requests left
     *
     * @return null|bool
     */
    public function isLimitReached()
    {
        return $this->isLimitReached;
    }

    /**
     * Count remaining requests
     *
     * @return null|int
     */
    public function getRemainingRequests()
    {
        return $this->remainingRequests;
    }

    /**
     * Requests left at the beginning of the month
     *
     * @return null|int
     */
    public function getAmountMonthStart()
    {
        return $this->amountMonthStart;
    }

    /**
     * Requests bought this month
     *
     * @return null|int
     */
    public function getAmountMonthBought()
    {
        return $this->amountMonthBought;
    }

    /**
     * Time that took the server to process the request.
     *
     * @return null|int
     */
    public function getDurationInMs()
    {
        return $this->durationInMs;
    }

    /**
     * @param \stdClass $response
     */
    public function parseResponse(\stdClass $response)
    {
        if (isset($response->key)) {
            $this->key = (string)$response->key;
        }

        if (isset($response->is_limit_reached)) {
            $this->isLimitReached = (bool)$response->is_limit_reached;
        }

        if (isset($response->remaining_requests)) {
            $this->remainingRequests = (int)$response->remaining_requests;
        }

        if (isset($response->amount_month_start)) {
            $this->amountMonthStart = (int)$response->amount_month_start;
        }

        if (isset($response->amount_month_bought)) {
            $this->amountMonthBought = $response->amount_month_bought;
        }

        if (isset($response->duration)) {
            $this->durationInMs = (int)$response->duration;
        }
    }

}