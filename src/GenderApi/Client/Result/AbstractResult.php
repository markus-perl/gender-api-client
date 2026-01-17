<?php

declare(strict_types=1);

namespace GenderApi\Client\Result;

/**
 * Abstract base class for API v2 results
 */
abstract class AbstractResult
{
    protected ?string $queryUrl = null;
    protected bool $resultFound = false;
    protected ?int $creditsUsed = null;
    protected ?int $durationInMs = null;

    abstract public function parseResponse(\stdClass $response): void;

    /**
     * Parse common v2 response fields
     */
    protected function parseV2Details(\stdClass $response): void
    {
        if (isset($response->result_found)) {
            $this->resultFound = (bool) $response->result_found;
        }

        if (isset($response->details) && $response->details instanceof \stdClass) {
            $details = $response->details;

            if (isset($details->credits_used)) {
                $this->creditsUsed = (int) $details->credits_used;
            }

            if (isset($details->duration)) {
                $duration = (string) $details->duration;
                $this->durationInMs = (int) preg_replace('/[^0-9]/', '', $duration);
            }
        }
    }

    public function __toString(): string
    {
        $result = PHP_EOL . static::class . PHP_EOL;
        $reflectionClass = new \ReflectionClass($this);
        $properties = $reflectionClass->getProperties();
        foreach ($properties as $property) {
            $name = $property->getName();
            $value = $this->$name;
            if (is_array($value)) {
                $value = json_encode($value);
            } elseif (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            }
            $result .= " " . $name . ': ' . $value . PHP_EOL;
        }

        return $result;
    }

    public function getQueryUrl(): ?string
    {
        return $this->queryUrl;
    }

    public function setQueryUrl(?string $queryUrl): void
    {
        $this->queryUrl = $queryUrl;
    }

    public function isResultFound(): bool
    {
        return $this->resultFound;
    }

    public function getCreditsUsed(): ?int
    {
        return $this->creditsUsed;
    }

    public function getDurationInMs(): ?int
    {
        return $this->durationInMs;
    }
}