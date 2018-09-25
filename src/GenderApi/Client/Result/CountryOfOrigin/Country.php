<?php

namespace GenderApi\Client\Result\CountryOfOrigin;


class Country
{
    /**
     * @var string
     */
    private $countryName;

    /**
     * @var string
     */
    private $country;

    /**
     * @var string
     */
    private $continentalRegion;

    /**
     * @var string
     */
    private $statisticalRegion;

    /**
     * @var float
     */
    private $probability;

    /**
     * @param \stdClass $response
     */
    public function parseResponse(\stdClass $response)
    {
        if (isset($response->country_name)) {
            $this->countryName = (string)$response->country_name;
        }

        if (isset($response->country)) {
            $this->country = (string)$response->country;
        }

        if (isset($response->continental_region)) {
            $this->continentalRegion = (string)$response->continental_region;
        }

        if (isset($response->statistical_region)) {
            $this->statisticalRegion = (string)$response->statistical_region;
        }

        if (isset($response->probability)) {
            $this->probability = (float)$response->probability;
        }
    }

    /**
     * @return string
     */
    public function getCountryName()
    {
        return $this->countryName;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return string
     */
    public function getContinentalRegion()
    {
        return $this->continentalRegion;
    }

    /**
     * @return string
     */
    public function getStatisticalRegion()
    {
        return $this->statisticalRegion;
    }

    /**
     * @return float
     */
    public function getProbability()
    {
        return $this->probability;
    }

}