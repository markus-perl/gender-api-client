Gender-API.com PHP Client
=========================

About
------------
PHP client for the Gender-API.com API.

Homepage: <https://gender-api.com>

FAQ: <https://gender-api.com/en/frequently-asked-questions>

API Docs: <https://gender-api.com/en/api-docs>

Contact: <https://gender-api.com/en/contact>

Installation
------------

```
my-project$ composer require gender-api/client
```

API-Key
-----------
Get a free API key here: <https://gender-api.com/en/account>

PHPUnit
------------

```
gender-api-client$ ./bin/phpunit
```

Simple Usage
---------

```php
use GenderApi\Client as GenderApiClient;

try {

    $apiClient = new GenderApiClient('insert your API key');
    $name = $apiClient->getByFirstName('elisabeth');

    if ($name->genderFound()) {
        echo $name->getGender(); // will return "female" (possible values: male, female, unknown)
    }

} catch (GenderApi\Exception $e) {
    // Name lookup failed due to a network error or insufficient requests left
    // See https://gender-api.com/en/api-docs/error-codes
    echo 'Exception: ' . $e->getMessage();
}
```

Advanced Usage
---------

```php
use GenderApi\Client as GenderApiClient;

try {

    $apiClient = new GenderApiClient('insert your API key');
    ````
    // Get gender by first name and country
    $name = $apiClient->getByFirstNameAndCountry('elisabeth', 'US');

    // Get gender by first name and client IP
    $name = $apiClient->getByFirstNameAndClientIpAddress('elisabeth', '178.27.52.144');

    // Get gender by first name and browser locale
    $name = $apiClient->getByFirstNameAndLocale('elisabeth', 'en_US');
    
    //Query multiple names with a single call
    foreach ($apiClient->getMultipleNames(array('stefan', 'elisabeth')) as $name) {
        if ($name->genderFound()) {
            echo $name->getName() . ': ' . $name->getGender(); // will return "female" (possible values: male, female, unknown)
        }
    }

} catch (GenderApi\Exception $e) {
    // Name lookup failed due to a network error or insufficient requests left
    // See https://gender-api.com/en/api-docs/error-codes
    echo 'Exception: ' . $e->getMessage();
}
```

Email Address
---------

```php
use GenderApi\Client as GenderApiClient;

try {

    $apiClient = new GenderApiClient('insert your API key');
    
    // Get gender by email address name and country
    $name = $apiClient->getByEmailAddress('elisabeth1499@gmail.com');
    if ($name->genderFound()) {
        echo $name->getGender(); // will return "female"
    }
    
    // Get gender by email address name and country
    $name = $apiClient->getByEmailAddressAndCountry('elisabeth.smith776@gmail.com', 'US');
    echo $name->getGender(); // will return "female"
    if ($name->genderFound()) {
        echo $name->getGender(); // will return "female"
    }
    
} catch (GenderApi\Exception $e) {
    // Name lookup failed due to a network error or insufficient requests left
    // See https://gender-api.com/en/api-docs/error-codes
    echo 'Exception: ' . $e->getMessage();
}
```

Split First and Last Name
---------

```php
use GenderApi\Client as GenderApiClient;

try {

    $apiClient = new GenderApiClient('insert your API key');
    
    // Get gender by email address name and country
    $name = $apiClient->getByFirstNameAndLastName('Frank Underwood');
 
    if ($name->genderFound()) {
        echo $name->getGender(); // will return "male"
        echo $name->getFirstName(); // will return "Frank"
        echo $name->getLastName(); // will return "Underwood"
    }

} catch (GenderApi\Exception $e) {
    // Name lookup failed due to a network error or insufficient requests left
    // See https://gender-api.com/en/api-docs/error-codes
    echo 'Exception: ' . $e->getMessage();
}
```


Statistics
---------

```php
use GenderApi\Client as GenderApiClient;

try {

    $apiClient = new GenderApiClient('insert your API key');
    
    $stats = $apiClient->getStats();
    
    // Check your query limit
    if ($stats->isLimitReached()) {
        echo "query limit reached.";
    }
    
    // Get remaining requests
    echo $stats->getRemainingRequests() . ' requests left.';

} catch (GenderApi\Exception $e) {
    // Name lookup failed due to a network error
    // See https://gender-api.com/en/api-docs/error-codes
    echo 'Exception: ' . $e->getMessage();
}
```

Proxy
---------
If you need a proxy server to access the Internet in your company, you can set one via the setProxy command.
```php
use GenderApi\Client as GenderApiClient;

$apiClient = new GenderApiClient('insert your API key');
$apiClient->setProxy('localhost', 3128);
```
