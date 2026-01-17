<?php

declare(strict_types=1);

namespace GenderApi\Client\Result;

/**
 * Result for full name (first + last name) gender lookup (API v2)
 */
class Split extends SingleName
{
    protected ?string $lastName = null;

    public function parseResponse(\stdClass $response): void
    {
        parent::parseResponse($response);

        if (isset($response->last_name)) {
            $this->lastName = (string) $response->last_name;
        }
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }
}