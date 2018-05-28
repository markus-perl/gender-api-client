<?php

namespace GenderApi;

use GenderApi\Client\CountryList;
use GenderApi\Client\Downloader;
use GenderApi\Client\InvalidParameterException;
use GenderApi\Client\Query;
use GenderApi\Client\Result;
use GenderApi\Client\RuntimeException;

/**
 * Class Client
 * @package GenderApi
 */
class Client
{
    /**
     * @var string
     */
    protected $apiKey = null;

    /**
     * @var string
     */
    protected $apiUrl = 'https://gender-api.com/';

    /**
     * @var CountryList
     */
    protected $countryList = null;

    /**
     * @var Downloader\AbstractDownloader
     */
    protected $downloader = null;

    /**
     * Client constructor.
     *
     * @param null|string $apiKey
     * @throws InvalidParameterException
     */
    public function __construct($apiKey = null)
    {
        if ($apiKey) {
            $this->setApiKey($apiKey);
        }

        $envApiUrl = getenv('APIURL');
        if ($envApiUrl) {
            $this->setApiUrl($envApiUrl);
        }

        $this->countryList = new CountryList();

        if (function_exists('curl_setopt')) {
            $this->downloader = new Downloader\Curl();
        } else {
            $this->downloader = new Downloader\FileGetContents();
        }
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     * @throws InvalidParameterException
     */
    public function setApiKey($apiKey)
    {
        if (!is_string($apiKey)) {
            throw new InvalidParameterException('Invalid Parameter for $apiKey. String expected, ' . gettype($apiKey) . ' given.');
        }

        $this->apiKey = $apiKey;
    }


    /**
     * @param string $apiUrl
     * @throws InvalidParameterException
     */
    public function setApiUrl($apiUrl)
    {
        if (!is_string($apiUrl)) {
            throw new InvalidParameterException('Invalid Parameter for $apiUrl. String expected, ' . gettype($apiUrl) . ' given.');
        }

        if (filter_var($apiUrl, FILTER_VALIDATE_URL) === false) {
            throw new InvalidParameterException('Parameter $apiUrl does not contain a valid url.');
        }

        $this->apiUrl = $apiUrl;
    }

    /**
     * @return string
     */
    public function getApiUrl()
    {
        return $this->apiUrl;
    }

    /**
     * @param Downloader\AbstractDownloader $downloader
     */
    public function setDownloader(Downloader\AbstractDownloader $downloader)
    {
        $this->downloader = $downloader;
    }

    /**
     * @return Downloader\AbstractDownloader
     */
    public function getDownloader()
    {
        return $this->downloader;
    }

    /**
     * @param $firstName
     * @param null|string $country
     * @param null|string $ipAddress
     * @param null|string $locale
     * @throws InvalidParameterException
     * @throws Client\RuntimeException
     * @throws Client\ApiException
     * @return Result\SingleName
     */
    protected function queryByFirstName($firstName, $country = null, $ipAddress = null, $locale = null)
    {
        if (!is_string($firstName)) {
            throw new InvalidParameterException('Invalid Parameter for $firstName. String expected, ' . gettype($firstName) . ' given.');
        }

        if (strlen($firstName) < 1 && strlen($firstName) > 100) {
            throw new InvalidParameterException('Invalid Parameter for $firstName. String with a minimum length of 1 and a max length of 100 expected.');
        }

        $countryCode = $this->getCountryCode($country);
        $ipAddress = $this->sanitizeIpAddress($ipAddress);
        $locale = $this->sanitizeLocale($locale);

        $query = $this->createQuery();
        $query->addParam('name', $firstName);
        $query->addParam('country', $countryCode);
        $query->addParam('ip', $ipAddress);
        $query->addParam('locale', $locale);

        $result = new Result\SingleName();
        $query->execute($result);
        return $result;
    }

    /**
     * @param string $firstName
     * @throws InvalidParameterException
     * @throws Client\RuntimeException
     * @throws Client\ApiException
     * @return Result\SingleName
     */
    public function getByFirstName($firstName)
    {
        return $this->queryByFirstName($firstName, null, null);
    }

    /**
     * @param string $firstName
     * @param string $country ISO 3166-2 country code. Example: 'US'
     * @throws InvalidParameterException
     * @throws Client\RuntimeException
     * @throws Client\ApiException
     * @return Result\SingleName
     */
    public function getByFirstNameAndCountry($firstName, $country)
    {
        return $this->queryByFirstName($firstName, $country, null);
    }

    /**
     * @param string $firstName
     * @param string $ipAddress
     * @throws InvalidParameterException
     * @throws Client\RuntimeException
     * @throws Client\ApiException
     * @return Result\SingleName
     */
    public function getByFirstNameAndClientIpAddress($firstName, $ipAddress)
    {
        return $this->queryByFirstName($firstName, null, $ipAddress);
    }

    /**
     * @param string $firstName
     * @param string $locale
     * @throws InvalidParameterException
     * @throws Client\RuntimeException
     * @throws Client\ApiException
     * @return Result\SingleName
     */
    public function getByFirstNameAndLocale($firstName, $locale)
    {
        return $this->queryByFirstName($firstName, null, null, $locale);
    }

    /**
     * @param array $firstNames
     * @param null $country
     * @return Result\MultipleNames
     * @throws InvalidParameterException
     * @throws Client\RuntimeException
     * @throws Client\ApiException
     */
    public function getByMultipleNames(array $firstNames)
    {
        return $this->getByMultipleNamesAndCountry($firstNames, '');
    }

    /**
     * @param array $firstNames
     * @param $country
     * @return Result\MultipleNames
     * @throws InvalidParameterException
     * @throws Client\RuntimeException
     * @throws Client\ApiException
     */
    public function getByMultipleNamesAndCountry(array $firstNames, $country)
    {
        foreach ($firstNames as $firstName) {
            if (!is_string($firstName)) {
                throw new InvalidParameterException('Invalid Parameter for $firstNames. Array with strings expected, ' . gettype($firstName) . ' given.');
            }

            if (strlen($firstName) < 1 && strlen($firstName) > 100) {
                throw new InvalidParameterException('Invalid Parameter for $firstName. String with a minimum length of 1 and a max length of 100 expected.');
            }
        }

        $countryCode = $this->getCountryCode($country);

        $query = $this->createQuery();
        $query->addParam('name', implode(';', $firstNames));
        $query->addParam('country', $countryCode);
        $query->addParam('multi', 'true');

        $result = new Result\MultipleNames();
        $query->execute($result);
        return $result;
    }

    /**
     * @param string $firstAndLastName
     * @param bool $strict
     * @return Result\Split
     * @throws InvalidParameterException
     * @throws Client\RuntimeException
     * @throws Client\ApiException
     */
    public function getByFirstNameAndLastName($firstAndLastName, $strict = false)
    {
        return $this->getByFirstNameAndLastNameAndCountry($firstAndLastName, '', $strict);
    }

    /**
     * @param string $firstAndLastName
     * @param string $country
     * @param bool $strict
     * @return Result\Split
     * @throws InvalidParameterException
     * @throws Client\RuntimeException
     * @throws Client\ApiException
     */
    public function getByFirstNameAndLastNameAndCountry($firstAndLastName, $country, $strict = false)
    {
        if (!is_string($firstAndLastName)) {
            throw new InvalidParameterException('Invalid Parameter for $firstAndLastName. String expected, ' . gettype($firstAndLastName) . ' given.');
        }

        if (!is_bool($strict)) {
            throw new InvalidParameterException('Invalid Parameter for $strict. String expected, ' . gettype($strict) . ' given.');
        }

        if (strlen($firstAndLastName) < 1 && strlen($firstAndLastName) > 150) {
            throw new InvalidParameterException('Invalid Parameter for $firstAndLastName. String with a minimum length of 1 and a max length of 150 expected.');
        }

        $countryCode = $this->getCountryCode($country);

        $query = $this->createQuery();
        $query->addParam('split', $firstAndLastName);
        $query->addParam('country', $countryCode);
        $query->addParam('strict', (int) $strict);

        $result = new Result\Split();
        $query->execute($result);
        return $result;
    }

    /**
     * @param string $emailAddress
     * @throws InvalidParameterException
     * @throws Client\RuntimeException
     * @throws Client\ApiException
     * @return Result\EmailAddress
     */
    public function getByEmailAddress($emailAddress)
    {
        return $this->getByEmailAddressAndCountry($emailAddress, '');
    }

    /**
     * @param string $emailAddress
     * @param string $country
     * @return Result\EmailAddress
     * @throws InvalidParameterException
     * @throws Client\RuntimeException
     * @throws Client\ApiException
     */
    public function getByEmailAddressAndCountry($emailAddress, $country)
    {
        if (!is_string($emailAddress)) {
            throw new InvalidParameterException('Invalid Parameter for $emailAddress. String expected, ' . gettype($emailAddress) . ' given.');
        }

        if (strlen($emailAddress) < 1 && strlen($emailAddress) > 100) {
            throw new InvalidParameterException('Invalid Parameter for $emailAddress. String with a minimum length of 1 and a max length of 100 expected.');
        }

        $countryCode = $this->getCountryCode($country);
        $emailAddress = $this->sanitizeEmailAddress($emailAddress);

        $query = $this->createQuery();
        $query->addParam('email', $emailAddress);
        $query->addParam('country', $countryCode);

        $result = new Result\EmailAddress();
        $query->execute($result);
        return $result;
    }

    /**
     * @throws Client\RuntimeException
     * @throws Client\ApiException
     * @return Result\Stats
     */
    public function getStats()
    {
        $query = $this->createQuery();
        $query->setMethod('get-stats');

        $result = new Result\Stats();
        $query->execute($result);
        return $result;
    }

    /**
     * @param null|string $ipAddress
     * @return null|string
     * @throws InvalidParameterException
     */
    protected function sanitizeIpAddress($ipAddress = null)
    {
        if (!$ipAddress) {
            return null;
        }

        if ($ipAddress && !is_string($ipAddress)) {
            throw new InvalidParameterException('Invalid Parameter for $ipAddress. String expected, ' . gettype($ipAddress) . ' given.');
        }

        if (filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false) {
            return $ipAddress;
        }

        if (filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false) {
            return $ipAddress;
        }

        throw new InvalidParameterException('Invalid Parameter for $ipAddress. Please provide a valid ip address. See https://gender-api.com/en/api-docs/localization-by-ip');
    }

    /**
     * @param null|string $locale
     * @return null|string
     * @throws InvalidParameterException
     */
    protected function sanitizeLocale($locale = null)
    {
        if (!$locale) {
            return null;
        }

        if ($this->countryList->isValidLocale($locale)) {
            return $locale;
        }

        throw new InvalidParameterException('Invalid Parameter for $locale. Please provide a valid locale. See https://gender-api.com/en/api-docs/localization-by-locale');
    }

    /**
     * @param null|string $emailAddress
     * @return null|string
     * @throws InvalidParameterException
     */
    protected function sanitizeEmailAddress($emailAddress)
    {

        if (filter_var($emailAddress, FILTER_VALIDATE_EMAIL) !== false) {
            return $emailAddress;
        }

        throw new InvalidParameterException('Invalid Parameter for $emailAddress. Please provide a valid email address. "' . $emailAddress . '" is not valid.');
    }

    /**
     * @throws Client\RuntimeException
     * @return Query
     */
    protected function createQuery()
    {
        if (!$this->getApiKey()) {
            throw new RuntimeException('API key missing. Please set your API key before calling this method.');
        }

        $query = new Query($this->getApiUrl(), $this->getDownloader());
        $query->addParam('key', $this->getApiKey());
        return $query;
    }

    /**
     * @param null|string $countryCode
     * @throws InvalidParameterException
     * @return string ISO 3166-2 country code
     */
    protected function getCountryCode($country = null)
    {
        if (!$country) {
            return null;
        }

        if ($country && !is_string($country)) {
            throw new InvalidParameterException('Invalid Parameter for $country. String expected, ' . gettype($country) . ' given.');
        }

        if ($this->countryList->isValidCountryCode($country)) {
            return strtoupper($country);
        }

        $countryCode = $this->countryList->getCountryCodeByName($country);
        if ($countryCode) {
            return $countryCode;
        }

        throw new InvalidParameterException('Invalid Parameter for $country. Please provide a valid country code or country name. See https://gender-api.com/en/api-docs/localization.');
    }

}