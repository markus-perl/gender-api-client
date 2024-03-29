<?php

namespace GenderApiTest;

use GenderApi\Client;

/**
 * Class TestCase
 * @package GenderApiTest
 */
class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @var bool
     */
    protected $doMock = true;

    /**
     * @var string
     */
    protected $apiKey = 'test-key';

    /**
     * @var string|null
     */
    protected $proxyHost = null;

    /**
     * @var int|null
     */
    protected $proxyPort = null;

    protected $apiUrl = 'https://gender-api.com/';

    /**
     * @return Client
     * @throws \GenderApi\Client\InvalidArgumentException
     */
    protected function getClient()
    {
        $genderApiClient = new Client($this->apiKey);
        $genderApiClient->setProxy($this->proxyHost, $this->proxyPort);
        $genderApiClient->setApiUrl($this->apiUrl);
        return $genderApiClient;
    }

    /**
     *
     */
    public function setUp(): void
    {
        parent::setUp();

        $apiKey = getenv('API_KEY');

        if ($apiKey) {
            $this->doMock = false;
            $this->apiKey = $apiKey;
        }

        $apiUrl = getenv('API_URL');
        if ($apiUrl) {
            $this->apiUrl = $apiUrl;
        }

        $proxyHost = getenv('PROXY_HOST');
        $proxyPort = getenv('PROXY_PORT');

        if ($proxyHost && $proxyPort) {
            $this->proxyHost = $proxyHost;
            $this->proxyPort = (int)$proxyPort;
        }
    }


}