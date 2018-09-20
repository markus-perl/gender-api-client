<?php

namespace GenderApiTest;

use GenderApi\Client;

/**
 * Class TestCase
 * @package GenderApiTest
 */
class TestCase extends \PHPUnit_Framework_TestCase
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

    /**
     * @return Client
     * @throws Client\InvalidParameterException
     */
    protected function getClient()
    {
        $genderApiClient = new Client($this->apiKey);
        $genderApiClient->setProxy($this->proxyHost, $this->proxyPort);
        return $genderApiClient;
    }

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();

        $apiKey = getenv('APIKEY');

        if ($apiKey) {
            $this->doMock = false;
            $this->apiKey = $apiKey;
        }

        $proxyHost = getenv('PROXY_HOST');
        $proxyPort = getenv('PROXY_PORT');

        if ($proxyHost && $proxyPort) {
            $this->proxyHost = $proxyHost;
            $this->proxyPort = (int)$proxyPort;
        }
    }


}