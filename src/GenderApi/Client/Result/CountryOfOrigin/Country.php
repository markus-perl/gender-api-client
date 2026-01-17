<?php

declare(strict_types=1);

namespace GenderApi\Client\Result\CountryOfOrigin;

/**
 * Country of origin data for a name
 */
class Country
{
    private ?string $countryName = null;

    private ?string $country = null;

    private ?string $continentalRegion = null;

    private ?string $statisticalRegion = null;

    private ?float $probability = null;

    public function parseResponse(\stdClass $response): void
    {
        if (isset($response->country_name)) {
            $this->countryName = (string) $response->country_name;
        }

        if (isset($response->country)) {
            $this->country = (string) $response->country;
        }

        if (isset($response->continental_region)) {
            $this->continentalRegion = (string) $response->continental_region;
        }

        if (isset($response->statistical_region)) {
            $this->statisticalRegion = (string) $response->statistical_region;
        }

        if (isset($response->probability)) {
            $this->probability = (float) $response->probability;
        }
    }

    public function getCountryName(): ?string
    {
        return $this->countryName;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function getContinentalRegion(): ?string
    {
        return $this->continentalRegion;
    }

    public function getStatisticalRegion(): ?string
    {
        return $this->statisticalRegion;
    }

    public function getProbability(): ?float
    {
        return $this->probability;
    }
}