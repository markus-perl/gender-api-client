<?php

namespace GenderApi\Client\Result;

/**
 * Class SingleName
 * @package GenderApi\Client\Result
 */
class SingleName extends AbstractResult
{
    /**
     * Submitted name
     *
     * @var null|string
     */
    protected $name = null;

    /**
     * The determined gender
     * Possible values: male, female, unknown
     *
     * @var null|string
     */
    protected $gender = null;

    /**
     * Number of records found in our database which are
     * matching your request
     *
     * @var null|int
     */
    protected $samples = null;

    /**
     * This value determines the reliability of our database.
     * A value of 100 means that the results on your gender
     * request are 100% accurate.
     *
     * @var null|int
     */
    protected $accuracy = null;

    /**
     * Time that took the server to process the request.
     *
     * @var null|int
     */
    protected $durationInMs = null;

    /**
     * The determined or submitted country code
     *
     * @var null|string
     */
    protected $country = null;

    /**
     * Submitted name
     *
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * The determined gender
     * Possible values: male, female, unknown
     *
     * @return null|string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Number of records found in our database which are
     * matching your request
     *
     * @return null|int
     */
    public function getSamples()
    {
        return $this->samples;
    }

    /**
     * This value determines the reliability of our database.
     * A value of 100 means that the results on your gender
     * request are 100% accurate.
     *
     * @return null|int
     */
    public function getAccuracy()
    {
        return $this->accuracy;
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
     * The determined or submitted country code
     *
     * @return null|string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Returns true if a gender was found matching the
     * submitted name
     *
     * @return bool
     */
    public function genderFound()
    {
        return $this->getGender() !== 'unknown' && $this->getGender() !== null;
    }

    /**
     * @param \stdClass $response
     */
    public function parseResponse(\stdClass $response)
    {
        if (isset($response->name)) {
            $this->name = (string)$response->name;
        }

        if (isset($response->gender)) {
            $this->gender = (string)$response->gender;
        }

        if (isset($response->samples)) {
            $this->samples = (int)$response->samples;
        }

        if (isset($response->accuracy)) {
            $this->accuracy = (int)$response->accuracy;
        }

        if (isset($response->duration)) {
            $this->durationInMs = (int)$response->duration;
        }

        if (isset($response->country)) {
            $this->country = $response->country;
        }
    }

}