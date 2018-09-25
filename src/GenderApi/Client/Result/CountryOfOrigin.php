<?php

namespace GenderApi\Client\Result;


use GenderApi\Client\Result\CountryOfOrigin\Country;

class CountryOfOrigin extends SingleName
{

    /**
     * Submitted name
     *
     * @var null|string
     */
    protected $countryOfOriginMapUrl = null;

    /**
     * @var array
     */
    protected $countryOfOrigin = array();

    /**
     * @return null|string
     */
    public function getCountryOfOriginMapUrl()
    {
        return $this->countryOfOriginMapUrl;
    }

    /**
     * @return array|Country[]
     */
    public function getCountryOfOrigin()
    {
        return $this->countryOfOrigin;
    }

    /**
     * @param \stdClass $response
     */
    public function parseResponse(\stdClass $response)
    {
        parent::parseResponse($response);

        if (isset($response->country_of_origin_map_url)) {
            $this->countryOfOriginMapUrl = $response->country_of_origin_map_url;
        }

        if (isset($response->country_of_origin)) {
            foreach ($response->country_of_origin as $countryOfOrigin) {
                $entry = new Country();
                $entry->parseResponse($countryOfOrigin);
                $this->countryOfOrigin[] = $entry;
            }
        }
    }

}