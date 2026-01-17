<?php

declare(strict_types=1);

namespace GenderApi\Client\Result;

/**
 * Result for single first name gender lookup (API v2)
 */
class SingleName extends AbstractResult
{
    protected ?string $firstName = null;

    protected ?string $gender = null;

    protected ?int $samples = null;

    protected ?float $probability = null;

    protected ?string $country = null;

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @deprecated Use getFirstName() instead
     */
    public function getName(): ?string
    {
        return $this->firstName;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function getSamples(): ?int
    {
        return $this->samples;
    }

    /**
     * Get probability as percentage (0-100)
     */
    public function getAccuracy(): ?int
    {
        if ($this->probability === null) {
            return null;
        }
        return (int) round($this->probability * 100);
    }

    /**
     * Get raw probability (0.0-1.0)
     */
    public function getProbability(): ?float
    {
        return $this->probability;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * Returns true if a gender was found matching the submitted name
     */
    public function genderFound(): bool
    {
        return $this->resultFound && $this->gender !== 'unknown' && $this->gender !== null;
    }

    public function parseResponse(\stdClass $response): void
    {
        $this->parseV2Details($response);

        // v2 API returns first_name
        if (isset($response->first_name)) {
            $this->firstName = (string) $response->first_name;
        }

        if (isset($response->gender)) {
            $this->gender = (string) $response->gender;
        }

        if (isset($response->probability)) {
            $this->probability = (float) $response->probability;
        }

        // samples is in details block
        if (isset($response->details) && isset($response->details->samples)) {
            $this->samples = (int) $response->details->samples;
        }

        // country is in details block
        if (isset($response->details) && isset($response->details->country)) {
            $this->country = (string) $response->details->country;
        }
    }
}