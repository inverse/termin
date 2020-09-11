<?php

declare(strict_types=1);

namespace Tests\Inverse\Termin;

use InvalidArgumentException;
use Inverse\Termin\SiteParser;
use PHPUnit\Framework\TestCase;

class SiteParserTest extends TestCase
{
    public function testParseValidSingleItem(): void
    {
        $payload = '[{"label":"Hello","url":"http://hello.com"}]';
        $siteParser = new SiteParser();
        $sites = $siteParser->parse($payload);
        self::assertNotEmpty($sites);
        self::assertEquals('Hello', $sites[0]->getLabel());
        self::assertEquals('http://hello.com', $sites[0]->getUrl());
    }

    public function testParseValidMultiple(): void
    {
        $payload = '[{"label":"Hello","url":"http://hello.com"},{"label":"Hello","url":"http://hello.com"}]';
        $siteParser = new SiteParser();
        $sites = $siteParser->parse($payload);
        self::assertNotEmpty($sites);
        self::assertEquals('Hello', $sites[0]->getLabel());
        self::assertEquals('http://hello.com', $sites[0]->getUrl());
        self::assertEquals('Hello', $sites[1]->getLabel());
        self::assertEquals('http://hello.com', $sites[1]->getUrl());
    }

    public function testParseMissingLabel(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $payload = '[{"url":"http://hello.com"}]';
        $siteParser = new SiteParser();
        $siteParser->parse($payload);
    }

    public function testParseMissingUrl(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $payload = '[{"label":"Hello"}]';
        $siteParser = new SiteParser();
        $siteParser->parse($payload);
    }

    public function testParseInvalidJson(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $payload = 'abc';
        $siteParser = new SiteParser();
        $siteParser->parse($payload);
    }
}
