<?php

declare(strict_types=1);

namespace GenderApiTest;

use Dotenv\Dotenv;
use GenderApi\Client;
use PHPUnit\Framework\TestCase as BaseTestCase;

/**
 * Base test case for GenderApi tests (API v2)
 */
class TestCase extends BaseTestCase
{
    protected bool $doMock = true;

    protected string $apiKey = 'test-key';

    protected ?string $proxyHost = null;

    protected ?int $proxyPort = null;

    protected string $apiUrl = 'https://gender-api.com/v2';

    private static bool $envLoaded = false;

    /**
     * @throws \GenderApi\Client\InvalidArgumentException
     */
    protected function getClient(): Client
    {
        $genderApiClient = new Client($this->apiKey);
        $genderApiClient->setProxy($this->proxyHost, $this->proxyPort);
        $genderApiClient->setApiUrl($this->apiUrl);
        return $genderApiClient;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadEnv();

        // Check for API key from .env (GENDER_API_KEY) or environment (API_KEY)
        $apiKey = $_ENV['GENDER_API_KEY'] ?? getenv('GENDER_API_KEY') ?: getenv('API_KEY');

        if ($apiKey !== false && $apiKey !== '' && $apiKey !== 'your_api_key_here') {
            $this->doMock = false;
            $this->apiKey = (string) $apiKey;
        }

        // Check for API URL from .env or environment
        $apiUrl = $_ENV['GENDER_API_URL'] ?? getenv('GENDER_API_URL') ?: getenv('API_URL');
        if ($apiUrl !== false && $apiUrl !== '') {
            $this->apiUrl = (string) $apiUrl;
        }

        $proxyHost = getenv('PROXY_HOST');
        $proxyPort = getenv('PROXY_PORT');

        if ($proxyHost !== false && $proxyPort !== false && $proxyHost !== '' && $proxyPort !== '') {
            $this->proxyHost = $proxyHost;
            $this->proxyPort = (int) $proxyPort;
        }
    }

    private function loadEnv(): void
    {
        if (self::$envLoaded) {
            return;
        }

        $envPath = dirname(__DIR__, 2);
        if (file_exists($envPath . '/.env')) {
            $dotenv = Dotenv::createImmutable($envPath);
            $dotenv->safeLoad();
        }

        self::$envLoaded = true;
    }
}