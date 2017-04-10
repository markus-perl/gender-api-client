<?php

namespace GenderApi\Client;

use GenderApiTest\TestCase;

/**
 * Class CountryListTest
 * @package GenderApi\Client
 */
class CountryListTest extends TestCase
{
    /**
     *
     */
    public function testGetCountryCodeByName()
    {
        $countryList = new CountryList();
        $this->assertEquals('DE', $countryList->getCountryCodeByName('Germany'));
        $this->assertNull($countryList->getCountryCodeByName('Bavaria'));
    }

    /**
     *
     */
    public function testIsValidCountryCode()
    {
        $countryList = new CountryList();
        $this->assertTrue($countryList->isValidCountryCode('AR'));
        $this->assertTrue($countryList->isValidCountryCode('be'));
        $this->assertFalse($countryList->isValidCountryCode('ZZ'));
    }

    /**
     *
     */
    public function testIsValidLocale()
    {
        $countryList = new CountryList();
        $this->assertTrue($countryList->isValidLocale('en_US'));
        $this->assertTrue($countryList->isValidLocale('de_DE'));
        $this->assertFalse($countryList->isValidLocale('en_XX'));
    }

}