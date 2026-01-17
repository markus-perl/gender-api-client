<?php

declare(strict_types=1);

namespace GenderApi;

use GenderApi\Client\CountryList;
use GenderApi\Client\Downloader;
use GenderApi\Client\Query;
use GenderApi\Client\Result;
use GenderApi\Client\RuntimeException;
use GenderApi\Client\InvalidArgumentException;

/**
 * Gender-API.com PHP Client v2
 *
 * @package GenderApi
 */
class Client
{
    protected ?string $apiKey = null;

    protected string $apiUrl = 'https://gender-api.com/v2';

    protected CountryList $countryList;

    protected Downloader\AbstractDownloader $downloader;

    /**
     * Client constructor.
     *
     * @throws InvalidArgumentException
     */
    public function __construct(?string $apiKey = null)
    {
        $this->countryList = new CountryList();

        // Initialize downloader first (before setApiKey which needs it)
        if (function_exists('curl_setopt')) {
            $this->downloader = new Downloader\Curl();
        } else {
            $this->downloader = new Downloader\FileGetContents();
        }

        if ($apiKey !== null) {
            $this->setApiKey($apiKey);
        }

        $envApiUrl = getenv('APIURL');
        if ($envApiUrl !== false && $envApiUrl !== '') {
            $this->setApiUrl($envApiUrl);
        }
    }

    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function setApiKey(string $apiKey): void
    {
        $this->apiKey = $apiKey;
        $this->downloader->setApiKey($apiKey);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function setApiUrl(string $apiUrl): void
    {
        if (filter_var($apiUrl, FILTER_VALIDATE_URL) === false) {
            throw new InvalidArgumentException('apiUrl does not contain a valid url.');
        }

        $this->apiUrl = rtrim($apiUrl, '/');
    }

    public function getApiUrl(): string
    {
        return $this->apiUrl;
    }

    public function setDownloader(Downloader\AbstractDownloader $downloader): void
    {
        $this->downloader = $downloader;
        if ($this->apiKey !== null) {
            $this->downloader->setApiKey($this->apiKey);
        }
    }

    public function getDownloader(): Downloader\AbstractDownloader
    {
        return $this->downloader;
    }

    /**
     * Set a proxy server for every request.
     *
     * @throws InvalidArgumentException
     */
    public function setProxy(?string $host = null, ?int $port = null): void
    {
        $this->downloader->setProxy($host, $port);
    }

    // ========================================================================
    // FIRST NAME ENDPOINTS
    // ========================================================================

    /**
     * Get gender by first name
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws Client\ApiException
     */
    public function getByFirstName(string $firstName): Result\SingleName
    {
        return $this->queryByFirstName($firstName);
    }

    /**
     * Get gender by first name and country
     *
     * @param string $country ISO 3166-2 country code. Example: 'US'
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws Client\ApiException
     */
    public function getByFirstNameAndCountry(string $firstName, string $country): Result\SingleName
    {
        return $this->queryByFirstName($firstName, $country);
    }

    /**
     * Get gender by first name, localized by client IP address
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws Client\ApiException
     */
    public function getByFirstNameAndClientIpAddress(string $firstName, string $ipAddress): Result\SingleName
    {
        return $this->queryByFirstName($firstName, null, $ipAddress);
    }

    /**
     * Get gender by first name, localized by browser locale
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws Client\ApiException
     */
    public function getByFirstNameAndLocale(string $firstName, string $locale): Result\SingleName
    {
        return $this->queryByFirstName($firstName, null, null, $locale);
    }

    /**
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws Client\ApiException
     */
    protected function queryByFirstName(
        string $firstName,
        ?string $country = null,
        ?string $ipAddress = null,
        ?string $locale = null
    ): Result\SingleName {
        $length = strlen($firstName);
        if ($length < 1 || $length > 100) {
            throw new InvalidArgumentException(
                "firstName expects a string with a minimum length of 1 and a max length of 100, given length: {$length}."
            );
        }

        $countryCode = $this->getCountryCode($country);
        $ipAddress = $this->sanitizeIpAddress($ipAddress);
        $locale = $this->sanitizeLocale($locale);

        $query = $this->createQuery('/gender/by-first-name');
        $query->setBodyParam('first_name', $firstName);
        $query->setBodyParam('country', $countryCode);
        $query->setBodyParam('ip', $ipAddress);
        $query->setBodyParam('locale', $locale);

        $result = new Result\SingleName();
        $query->execute($result);
        return $result;
    }

    // ========================================================================
    // MULTIPLE FIRST NAMES ENDPOINT
    // ========================================================================

    /**
     * Get gender for multiple first names
     *
     * @param array<string> $firstNames
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws Client\ApiException
     */
    public function getByMultipleNames(array $firstNames): Result\MultipleNames
    {
        return $this->getByMultipleNamesAndCountry($firstNames, null);
    }

    /**
     * Get gender for multiple first names with country filter
     *
     * @param array<string> $firstNames
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws Client\ApiException
     */
    public function getByMultipleNamesAndCountry(array $firstNames, ?string $country): Result\MultipleNames
    {
        $body = [];
        $countryCode = $this->getCountryCode($country);

        foreach ($firstNames as $index => $firstName) {
            $firstName = (string) $firstName;

            $length = strlen($firstName);
            if ($length < 1 || $length > 100) {
                throw new InvalidArgumentException(
                    'firstNames expects an array with strings with a minimum length of 1 and a max length of 100'
                );
            }

            $item = [
                'id' => (string) ($index + 1),
                'first_name' => $firstName,
            ];
            if ($countryCode !== null) {
                $item['country'] = $countryCode;
            }
            $body[] = $item;
        }

        $query = $this->createQuery('/gender/by-first-name-multiple');
        $query->setBody($body);

        $result = new Result\MultipleNames();
        $query->execute($result);
        return $result;
    }

    // ========================================================================
    // FULL NAME (SPLIT) ENDPOINTS
    // ========================================================================

    /**
     * Get gender by full name (first name + last name)
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws Client\ApiException
     */
    public function getByFirstNameAndLastName(string $fullName): Result\Split
    {
        return $this->getByFirstNameAndLastNameAndCountry($fullName, null);
    }

    /**
     * Get gender by full name with country filter
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws Client\ApiException
     */
    public function getByFirstNameAndLastNameAndCountry(string $fullName, ?string $country): Result\Split
    {
        $length = strlen($fullName);
        if ($length < 1 || $length > 150) {
            throw new InvalidArgumentException(
                "fullName expects a string with a minimum length of 1 and a max length of 150, given length: {$length}."
            );
        }

        $countryCode = $this->getCountryCode($country);

        $query = $this->createQuery('/gender/by-full-name');
        $query->setBodyParam('full_name', $fullName);
        $query->setBodyParam('country', $countryCode);

        $result = new Result\Split();
        $query->execute($result);
        return $result;
    }

    // ========================================================================
    // EMAIL ADDRESS ENDPOINTS
    // ========================================================================

    /**
     * Get gender by email address
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws Client\ApiException
     */
    public function getByEmailAddress(string $emailAddress): Result\EmailAddress
    {
        return $this->getByEmailAddressAndCountry($emailAddress, null);
    }

    /**
     * Get gender by email address with country filter
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws Client\ApiException
     */
    public function getByEmailAddressAndCountry(string $emailAddress, ?string $country): Result\EmailAddress
    {
        $length = strlen($emailAddress);
        if ($length < 1 || $length > 100) {
            throw new InvalidArgumentException(
                "emailAddress expects a string with a minimum length of 1 and a max length of 100, given length: {$length}."
            );
        }

        $countryCode = $this->getCountryCode($country);
        $emailAddress = $this->sanitizeEmailAddress($emailAddress);

        $query = $this->createQuery('/gender/by-email-address');
        $query->setBodyParam('email', $emailAddress);
        $query->setBodyParam('country', $countryCode);

        $result = new Result\EmailAddress();
        $query->execute($result);
        return $result;
    }

    // ========================================================================
    // STATISTICS ENDPOINT
    // ========================================================================

    /**
     * Get account statistics (remaining credits, usage, etc.)
     *
     * @throws RuntimeException
     * @throws Client\ApiException
     */
    public function getStats(): Result\Stats
    {
        $query = $this->createQuery('/statistic', 'GET');

        $result = new Result\Stats();
        $query->execute($result);
        return $result;
    }

    // ========================================================================
    // COUNTRY OF ORIGIN ENDPOINT
    // ========================================================================

    /**
     * Get country of origin for a first name
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws Client\ApiException
     */
    public function getCountryOfOrigin(string $firstName): Result\CountryOfOrigin
    {
        $length = strlen($firstName);
        if ($length < 1 || $length > 100) {
            throw new InvalidArgumentException(
                "firstName expects a string with a minimum length of 1 and a max length of 100, given length: {$length}."
            );
        }

        $query = $this->createQuery('/country-of-origin');
        $query->setBodyParam('first_name', $firstName);

        $result = new Result\CountryOfOrigin();
        $query->execute($result);
        return $result;
    }

    // ========================================================================
    // VALIDATION HELPERS
    // ========================================================================

    /**
     * @throws InvalidArgumentException
     */
    protected function sanitizeIpAddress(?string $ipAddress = null): ?string
    {
        if ($ipAddress === null || $ipAddress === '') {
            return null;
        }

        if (filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false) {
            return $ipAddress;
        }

        if (filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false) {
            return $ipAddress;
        }

        throw new InvalidArgumentException(
            'Invalid ipAddress. Please provide a valid ip address. See https://gender-api.com/en/api-docs/localization-by-ip'
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    protected function sanitizeLocale(?string $locale = null): ?string
    {
        if ($locale === null || $locale === '') {
            return null;
        }

        if ($this->countryList->isValidLocale($locale)) {
            return $locale;
        }

        throw new InvalidArgumentException(
            'locale expects a string. A list of valid locales can be found here https://gender-api.com/en/api-docs/localization-by-locale'
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    protected function sanitizeEmailAddress(string $emailAddress): string
    {
        if (filter_var($emailAddress, FILTER_VALIDATE_EMAIL) !== false) {
            return $emailAddress;
        }

        throw new InvalidArgumentException(
            "emailAddress expects a valid email address, given: {$emailAddress}."
        );
    }

    /**
     * @throws RuntimeException
     */
    protected function createQuery(string $endpoint, string $method = 'POST'): Query
    {
        if ($this->getApiKey() === null) {
            throw new RuntimeException('API key missing. Please set your API key before calling this method.');
        }

        // Ensure downloader has the API key
        $this->downloader->setApiKey($this->apiKey);

        return new Query($this->getApiUrl(), $this->getDownloader(), $endpoint, $method);
    }

    /**
     * @throws InvalidArgumentException
     * @return string|null ISO 3166-2 country code
     */
    protected function getCountryCode(?string $country = null): ?string
    {
        if ($country === null || $country === '') {
            return null;
        }

        if ($this->countryList->isValidCountryCode($country)) {
            return strtoupper($country);
        }

        $countryCode = $this->countryList->getCountryCodeByName($country);
        if ($countryCode !== null) {
            return $countryCode;
        }

        throw new InvalidArgumentException(
            'Invalid country code. Please provide a valid country code or country name. See https://gender-api.com/en/api-docs/localization.'
        );
    }
}