<?php

namespace GenderApi\Client\Result;

/**
 * Class EmailAddress
 * @package GenderApi\Client\Result
 */
class EmailAddress extends SingleName
{

    /**
     * Last name found in email address
     *
     * @var null|string
     */
    protected $lastName;

    /**
     * Submitted email address
     *
     * @var null|string
     */
    protected $emailAddress = null;

    /**
     * Domain Name without TLD
     *
     * @var null|string
     */
    protected $emailProvider = null;

    /**
     * @param \stdClass $response
     */
    public function parseResponse(\stdClass $response)
    {
        parent::parseResponse($response);

        if (isset($response->lastname)) {
            $this->lastName = (string)$response->lastname;
        }

        if (isset($response->email)) {
            $this->emailAddress = (string)$response->email;
        }

        if (isset($response->mailprovider)) {
            $this->emailProvider = (string)$response->mailprovider;
        }
    }

    /**
     * Last name found in email address
     *
     * @return null|string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Submitted email address
     *
     * @return null|string
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * Domain Name without TLD
     *
     * @return null|string
     */
    public function getMailProvider()
    {
        return $this->emailProvider;
    }

}