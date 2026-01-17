# Gender-API.com PHP Client

[![PHP Version](https://img.shields.io/badge/PHP-8.3%2B-blue.svg)](https://www.php.net/)
[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![PHPStan Level](https://img.shields.io/badge/PHPStan-Level%206-brightgreen.svg)](phpstan.neon)

A modern, type-safe PHP client for the [Gender-API.com](https://gender-api.com) service. Determine gender from first names, full names, or email addresses with high accuracy across 200+ countries.

## ✨ Features

- 🔍 **Name Gender Detection** - Detect gender from first names with 99%+ accuracy
- 🌍 **Country Localization** - Improve accuracy with country-specific results
- 📧 **Email Analysis** - Extract and analyze names from email addresses
- 🗺️ **Country of Origin** - Discover name origins and geographic distribution
- 📊 **Batch Processing** - Query multiple names in a single API call
- 🔒 **Type-Safe** - Full PHP 8.3+ support with strict typing
- ⚡ **Modern** - PSR-4 autoloading, PHPUnit 11, PHPStan Level 6

## 📦 Installation

Install via [Composer](https://getcomposer.org/):

```bash
composer require gender-api/client
```

## 🔑 API Key

Get your free API key at: **[gender-api.com/account](https://gender-api.com/en/account)**

## 🚀 Quick Start

```php
<?php

use GenderApi\Client as GenderApiClient;

$client = new GenderApiClient('your-api-key');

// Simple gender lookup
$result = $client->getByFirstName('Elisabeth');

if ($result->genderFound()) {
    echo $result->getGender();    // "female"
    echo $result->getAccuracy();  // 99
}
```

## 📖 Usage Examples

### First Name with Country

For names that vary by country (e.g., "Andrea" is male in Italy, female in Germany):

```php
// In Italy, Andrea is typically male
$result = $client->getByFirstNameAndCountry('Andrea', 'IT');
echo $result->getGender(); // "male"

// In Germany, Andrea is typically female
$result = $client->getByFirstNameAndCountry('Andrea', 'DE');
echo $result->getGender(); // "female"
```

### First Name with Localization

Detect country from IP address or browser locale:

```php
// Localize by IP address
$result = $client->getByFirstNameAndClientIpAddress('Jan', '178.27.52.144');

// Localize by browser locale
$result = $client->getByFirstNameAndLocale('Jan', 'de_DE');
```

### Full Name (First + Last Name Split)

The API automatically splits full names and identifies the first name:

```php
$result = $client->getByFirstNameAndLastName('Sandra Miller');

echo $result->getFirstName(); // "Sandra"
echo $result->getLastName();  // "Miller"
echo $result->getGender();    // "female"
```

With country and strict mode:

```php
$result = $client->getByFirstNameAndLastNameAndCountry(
    'Maria Garcia',
    'ES',
    strict: true
);
```

### Email Address

Extract and analyze names from email addresses:

```php
$result = $client->getByEmailAddress('elisabeth.smith@company.com');

echo $result->getGender();       // "female"
echo $result->getFirstName();    // "Elisabeth" (extracted)
echo $result->getLastName();     // "Smith"
```

### Multiple Names (Batch)

Query multiple names in a single API call for efficiency:

```php
$names = ['Michael', 'Sarah', 'Kim', 'Jordan'];
$results = $client->getByMultipleNames($names);

foreach ($results as $result) {
    printf(
        "%s: %s (%d%% confidence)\n",
        $result->getFirstName(),
        $result->getGender(),
        $result->getAccuracy()
    );
}
// Output:
// Michael: male (99% confidence)
// Sarah: female (99% confidence)
// Kim: female (72% confidence)
// Jordan: male (68% confidence)
```

With country filter:

```php
$results = $client->getByMultipleNamesAndCountry(['Andrea', 'Nicola'], 'IT');
```

### Country of Origin

Discover where a name originates from:

```php
$result = $client->getCountryOfOrigin('Giuseppe');

echo $result->getGender(); // "male"

foreach ($result->getCountryOfOrigin() as $country) {
    printf(
        "%s (%s): %.0f%%\n",
        $country->getCountryName(),
        $country->getCountry(),
        $country->getProbability() * 100
    );
}
// Output:
// Italy (IT): 89%
// Argentina (AR): 4%
// United States (US): 3%

// Get interactive map URL
echo $result->getCountryOfOriginMapUrl();
```

### Account Statistics

Monitor your API usage:

```php
$stats = $client->getStats();

echo $stats->getRemainingCredits(); // 4523
echo $stats->isLimitReached();       // false
```

## ⚙️ Configuration

### Proxy Server

```php
$client = new GenderApiClient('your-api-key');
$client->setProxy('proxy.company.com', 3128);
```

### Custom API URL

For enterprise or on-premise installations:

```php
$client = new GenderApiClient('your-api-key');
$client->setApiUrl('https://custom-api.example.com/');
```

## 🧪 Development

### Requirements

- PHP 8.3 or higher
- Composer

### Setup

```bash
# Clone the repository
git clone https://github.com/markus-perl/gender-api-client.git
cd gender-api-client

# Install dependencies
composer install

# Start Docker environment (optional)
docker-compose up -d
```

### Running Tests

```bash
# Unit tests (with mocked data)
./vendor/bin/phpunit --testsuite unit

# Integration tests (requires API key in .env)
cp .env.example .env
# Edit .env and add your API key
./vendor/bin/phpunit --testsuite integration

# All tests
./vendor/bin/phpunit --testsuite unit,integration
```

### Static Analysis

```bash
./vendor/bin/phpstan analyse
```

## 🔍 API Response Properties

### SingleName Result

| Property | Type | Description |
|----------|------|-------------|
| `getFirstName()` | `?string` | The queried name |
| `getProbability()` | `?float` | Probability (e.g. 0.99) |
| `getGender()` | `?string` | `"male"`, `"female"`, or `"unknown"` |
| `getAccuracy()` | `?int` | Confidence percentage (0-100) |
| `getSamples()` | `?int` | Number of data samples |
| `getCountry()` | `?string` | ISO 3166-1 country code |
| `genderFound()` | `bool` | Whether a gender was determined |

### Stats Result

| Property | Type | Description |
|----------|------|-------------|
| `getRemainingCredits()` | `?int` | API credits remaining |
| `isLimitReached()` | `?bool` | Whether quota is exhausted |

## 🛠️ Error Handling

```php
use GenderApi\Client as GenderApiClient;
use GenderApi\Client\ApiException;
use GenderApi\Client\RuntimeException;
use GenderApi\Client\InvalidArgumentException;
use GenderApi\Client\Downloader\NetworkErrorException;

try {
    $result = $client->getByFirstName('Elisabeth');
} catch (InvalidArgumentException $e) {
    // Invalid input parameters
} catch (ApiException $e) {
    // API returned an error (e.g., invalid key, limit exceeded)
    echo $e->getCode();    // Error code
    echo $e->getMessage(); // Error message
} catch (NetworkErrorException $e) {
    // Network connectivity issues
} catch (RuntimeException $e) {
    // Other runtime errors (e.g., missing API key)
}
```

## 📚 Resources

- **Homepage**: [gender-api.com](https://gender-api.com)
- **API Documentation**: [gender-api.com/api-docs](https://gender-api.com/en/api-docs)
- **OpenAPI Specification**: [docs/openapi.yml](docs/openapi.yml)
- **FAQ**: [gender-api.com/faq](https://gender-api.com/en/frequently-asked-questions)
- **Error Codes**: [gender-api.com/error-codes](https://gender-api.com/en/api-docs/error-codes)
- **Support**: [gender-api.com/contact](https://gender-api.com/en/contact)

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request
