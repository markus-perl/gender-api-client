<?php

declare(strict_types=1);

namespace GenderApi\Client\Result;

/**
 * Result for email address gender lookup (API v2)
 */
class EmailAddress extends SingleName
{
    protected ?string $lastName = null;

    protected ?string $emailAddress = null;

    public function parseResponse(\stdClass $response): void
    {
        parent::parseResponse($response);

        if (isset($response->last_name)) {
            $this->lastName = (string) $response->last_name;
        }

        // Email is in the input block for v2
        if (isset($response->input) && isset($response->input->email)) {
            $this->emailAddress = (string) $response->input->email;
        }
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getEmailAddress(): ?string
    {
        return $this->emailAddress;
    }
}