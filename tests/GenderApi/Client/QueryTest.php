<?php

declare(strict_types=1);

namespace GenderApi\Client;

use GenderApi\Client\Downloader\FileGetContents;
use GenderApi\Client\Result\SingleName;
use GenderApiTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Tests for Query class (API v2)
 */
class QueryTest extends TestCase
{
    #[Test]
    public function testBodyParams(): void
    {
        $downloader = $this->createMock(FileGetContents::class);
        $query = new Query('/mock-url/', $downloader, '/gender/by-first-name');

        $query->setBodyParam('first_name', 'Sandra');
        $query->setBodyParam('country', 'US');

        $this->assertEquals([
            'first_name' => 'Sandra',
            'country' => 'US',
        ], $query->getBody());
    }

    #[Test]
    public function testEndpoint(): void
    {
        $downloader = $this->createMock(FileGetContents::class);
        $query = new Query('/mock-url', $downloader, '/gender/by-first-name');

        $this->assertEquals('/gender/by-first-name', $query->getEndpoint());

        $query->setEndpoint('/gender/by-email-address');
        $this->assertEquals('/gender/by-email-address', $query->getEndpoint());
    }

    #[Test]
    public function testExecute(): void
    {
        $downloader = $this->createMock(FileGetContents::class);
        $downloader->method('request')
            ->willReturn('{"input":{"first_name":"johanna"},"details":{"credits_used":1,"duration":"15ms","samples":15895},"result_found":true,"first_name":"Johanna","probability":0.98,"gender":"female"}');

        $query = new Query('/mock-url/', $downloader, '/gender/by-first-name');
        $query->setBodyParam('first_name', 'johanna');

        $result = new SingleName();
        $query->execute($result);

        $this->assertEquals('female', $result->getGender());
    }

    #[Test]
    public function testExecuteWithError(): void
    {
        $this->expectException(ApiException::class);

        $downloader = $this->createMock(FileGetContents::class);
        $downloader->method('request')
            ->willReturn('{"errmsg":"limit reached","errno":30}');

        $query = new Query('/mock-url/', $downloader, '/gender/by-first-name');
        $query->setBodyParam('first_name', 'markus');

        $result = new SingleName();
        $query->execute($result);
    }

    #[Test]
    public function testExecuteWithInvalidJson(): void
    {
        $this->expectException(RuntimeException::class);

        $downloader = $this->createMock(FileGetContents::class);
        $downloader->method('request')
            ->willReturn('503 internal server error');

        $query = new Query('/mock-url/', $downloader, '/gender/by-first-name');
        $query->setBodyParam('first_name', 'markus');

        $result = new SingleName();
        $query->execute($result);
    }
}