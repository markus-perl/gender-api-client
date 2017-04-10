<?php

namespace GenderApi\Client\Result;

/**
 * Class Split
 * @package GenderApi\Client\Result
 */
class Split extends SingleName
{

    /**
     * The last name found
     *
     * @var null|string
     */
    protected $lastName;

    /**
     * The first name found
     *
     * @var null|string
     */
    protected $firstName;

    /**
     * Shows if the strict mode is enabled. Default: false.
     * If the strict mode is enabled, gender-api will return null in the last_name
     * field if the last_name cannot be found in the database. If the strict mode
     * is disabled, gender-api will try to extract the last name even if it
     * cannot be found in our database.
     *
     * @var null|bool
     */
    protected $strict;

    /**
     * @param \stdClass $response
     */
    public function parseResponse(\stdClass $response)
    {
        parent::parseResponse($response);

        if (isset($response->first_name)) {
            $this->firstName = (string)$response->first_name;
        }

        if (isset($response->last_name)) {
            $this->lastName = (string)$response->last_name;
        }

        if (isset($response->strict)) {
            $this->strict = (bool)$response->strict;
        }
    }

    /**
     * The last name found
     *
     * @return null|string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @return null|string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @return null|bool
     */
    public function getStrict()
    {
        return $this->strict;
    }


}