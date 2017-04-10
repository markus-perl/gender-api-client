<?php

namespace GenderApiTest;

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
    }


}