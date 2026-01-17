<?php

declare(strict_types=1);

namespace GenderApi\Client\Result;

use GenderApi\Client\Result\CountryOfOrigin\Country;

/**
 * Result for country of origin lookup
 */
class CountryOfOrigin extends SingleName
{
    protected ?string $countryOfOriginMapUrl = null;

    /** @var array<Country> */
    protected array $countryOfOrigin = [];

    public function getCountryOfOriginMapUrl(): ?string
    {
        return $this->countryOfOriginMapUrl;
    }

    /**
     * @return array<Country>
     */
    public function getCountryOfOrigin(): array
    {
        return $this->countryOfOrigin;
    }

    public function parseResponse(\stdClass $response): void
    {
        parent::parseResponse($response);

        if (isset($response->country_of_origin_map_url)) {
            $this->countryOfOriginMapUrl = (string) $response->country_of_origin_map_url;
        }

        if (isset($response->country_of_origin) && is_array($response->country_of_origin)) {
            foreach ($response->country_of_origin as $countryOfOrigin) {
                $entry = new Country();
                $entry->parseResponse($countryOfOrigin);
                $this->countryOfOrigin[] = $entry;
            }
        }
    }
}