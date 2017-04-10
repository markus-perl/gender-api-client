<?php

namespace GenderApi\Client;

use GenderApi\Client\Downloader\FileGetContents;
use GenderApi\Client\Result\SingleName;
use GenderApiTest\TestCase;

/**
 * Class QueryTest
 * @package GenderApi\Client
 */
class QueryTest extends TestCase
{

    /**
     *
     */
    public function testParams()
    {
        /* @var FileGetContents|\PHPUnit_Framework_MockObject_MockObject $downloader */
        $downloader = $this->createMock(FileGetContents::class);
        $query = new Query('/mock-url/', $downloader);

        $query->addParam('key1', 'value1');
        $query->addParam('key2', 'value2');

        $this->assertEquals(array(
            'key1' => 'value1',
            'key2' => 'value2',
        ), $query->getParams());
    }

    /**
     *
     */
    public function testMethod()
    {
        /* @var FileGetContents|\PHPUnit_Framework_MockObject_MockObject $downloader */
        $downloader = $this->createMock(FileGetContents::class);
        $query = new Query('/mock-url', $downloader);

        $this->assertEquals('get', $query->getMethod());

        $query->setMethod('get-stats');
        $this->assertEquals('get-stats', $query->getMethod());
    }

    public function testExecute()
    {
        /* @var FileGetContents|\PHPUnit_Framework_MockObject_MockObject $downloader */
        $downloader = $this->createMock(FileGetContents::class);
        $downloader->method('download')->with('/mock-url/get?key1=value1')
            ->will($this->returnValue('{"name":"johanna","gender":"female","samples":15895,"accuracy":98,"duration":"15ms"}'));
        $query = new Query('/mock-url/', $downloader);

        $query->addParam('key1', 'value1');

        $result = new SingleName();
        $query->execute($result);

        $this->assertEquals('female', $result->getGender());
    }

    /**
     * @expectedException \GenderApi\Client\ApiException
     */
    public function testExecuteWithError()
    {
        /* @var FileGetContents|\PHPUnit_Framework_MockObject_MockObject $downloader */
        $downloader = $this->createMock(FileGetContents::class);
        $downloader->method('download')->with('/mock-url/get?key1=value1')
            ->will($this->returnValue('{"name":"markus","errno":30,"errmsg":"limit reached. thank you for using our service. please create an account to increase your daily limit and get 500 requests free per month or to buy more requests.","gender":"unknown","samples":0,"accuracy":0,"duration":"120ms"}'));
        $query = new Query('/mock-url/', $downloader);

        $query->addParam('key1', 'value1');

        $result = new SingleName();
        $query->execute($result);
    }

    /**
     * @expectedException \GenderApi\Client\Exception
     */
    public function testExecuteWithInvalidJson()
    {
        /* @var FileGetContents|\PHPUnit_Framework_MockObject_MockObject $downloader */
        $downloader = $this->createMock(FileGetContents::class);
        $downloader->method('download')->with('/mock-url/get?key1=value1')
            ->will($this->returnValue('503 internal server error'));
        $query = new Query('/mock-url/', $downloader);

        $query->addParam('key1', 'value1');

        $result = new SingleName();
        $query->execute($result);
    }
}