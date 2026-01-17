<?php

declare(strict_types=1);

namespace GenderApiIntegration;

use Dotenv\Dotenv;
use GenderApi\Client;
use PHPUnit\Framework\TestCase;

/**
 * Base class for integration tests
 *
 * Loads configuration from .env file and provides common setup.
 */
abstract class IntegrationTestCase extends TestCase
{
    protected Client $client;
    protected static bool $envLoaded = false;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadEnv();

        $apiKey = $_ENV['GENDER_API_KEY'] ?? getenv('GENDER_API_KEY');
        $apiUrl = $_ENV['GENDER_API_URL'] ?? getenv('GENDER_API_URL') ?: 'https://gender-api.com/';

        if (empty($apiKey) || $apiKey === 'your_api_key_here') {
            $this->markTestSkipped(
                'Integration tests require GENDER_API_KEY in .env file or environment variable.'
            );
        }

        $this->client = new Client($apiKey);
        $this->client->setApiUrl($apiUrl);
    }

    private function loadEnv(): void
    {
        if (self::$envLoaded) {
            return;
        }

        $envPath = dirname(__DIR__);
        if (file_exists($envPath . '/.env')) {
            $dotenv = Dotenv::createImmutable($envPath);
            $dotenv->safeLoad();
        }

        self::$envLoaded = true;
    }
}
