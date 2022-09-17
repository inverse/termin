<?php

declare(strict_types=1);

namespace Tests\Inverse\Termin;

use DateTime;
use Inverse\Termin\Config\Config;
use Inverse\Termin\Config\Site;
use Inverse\Termin\Exceptions\TerminException;
use Inverse\Termin\Filter;
use Inverse\Termin\Notifier\MultiNotifier;
use Inverse\Termin\Result;
use Inverse\Termin\Scraper\BerlinServiceScraper;
use Inverse\Termin\Scraper\ScraperLocator;
use Inverse\Termin\Termin;
use PHPUnit\Framework\TestCase;
use Psr\Log\Test\TestLogger;
use Tests\Inverse\Termin\Notifier\TestNotifier;

class TerminTest extends TestCase
{
    public function testExceptionRaised(): void
    {
        $mockScraper = $this->createMock(BerlinServiceScraper::class);

        $mockScraper->method('scrape')
            ->will($this->throwException(new TerminException()))
        ;

        $mockConfig = $this->createMock(Config::class);

        $testLogger = new TestLogger();
        $testNotifier = new TestNotifier();
        $multiNotifier = new MultiNotifier();
        $multiNotifier->addNotifier($testNotifier);

        $filter = new Filter([]);

        $termin = new Termin(new ScraperLocator(['berlin_services' => $mockScraper]), $testLogger, $multiNotifier, $filter, $mockConfig);

        $termin->run([new Site('hello', 'berlin_services', ['url' => 'https://hello.com'])]);

        self::assertTrue($testLogger->hasErrorRecords());
    }

    public function testMatchFound(): void
    {
        $mockScraper = $this->createMock(BerlinServiceScraper::class);

        $mockScraper->method('scrape')
            ->willReturn([new Result('https://example.com', 'Hello', new DateTime('2020-01-01 00:00:00'))])
        ;

        $mockConfig = $this->createMock(Config::class);
        $mockConfig->method('isAllowMultipleNotifications')
            ->willReturn(true)
        ;

        $testLogger = new TestLogger();
        $testNotifier = new TestNotifier();
        $multiNotifier = new MultiNotifier();
        $multiNotifier->addNotifier($testNotifier);

        $filter = new Filter([]);

        $termin = new Termin(new ScraperLocator(['berlin_services' => $mockScraper]), $testLogger, $multiNotifier, $filter, $mockConfig);

        $termin->run([new Site('hello', 'berlin_services', ['url' => 'https://hello.com'])]);

        self::assertNotEmpty($testNotifier->getNotifications());
        self::assertTrue($testLogger->hasInfoThatContains('Found availability for hello @ 2020-01-01T00:00:00+00:00'));
    }

    public function testMatchMultipleFound(): void
    {
        $mockScraper = $this->createMock(BerlinServiceScraper::class);

        $mockScraper->method('scrape')
            ->willReturn([
                new Result('https://example.com', 'Hello', new DateTime('2020-01-01 00:00:00')),
                new Result('https://example.com', 'Hello', new DateTime('2020-01-02 00:00:00')),
            ])
        ;

        $mockConfig = $this->createMock(Config::class);
        $mockConfig->method('isAllowMultipleNotifications')
            ->willReturn(false)
        ;

        $testLogger = new TestLogger();
        $testNotifier = new TestNotifier();
        $multiNotifier = new MultiNotifier();
        $multiNotifier->addNotifier($testNotifier);

        $filter = new Filter([]);

        $termin = new Termin(new ScraperLocator(['berlin_services' => $mockScraper]), $testLogger, $multiNotifier, $filter, $mockConfig);

        $termin->run([new Site('hello', 'berlin_services', [])]);

        self::assertNotEmpty($testNotifier->getNotifications());
        self::assertTrue($testLogger->hasInfoThatContains('Found availability for hello @ 2020-01-01T00:00:00+00:00'));
        self::assertTrue($testLogger->hasDebugThatContains('2 results found, skipping due to allow_multiple_notifications being false'));
    }

    public function testMatchNotFound(): void
    {
        $mockScraper = $this->createMock(BerlinServiceScraper::class);

        $mockScraper->method('scrape')
            ->willReturn([])
        ;

        $mockConfig = $this->createMock(Config::class);
        $mockConfig->method('isAllowMultipleNotifications')
            ->willReturn(true)
        ;

        $testNotifier = new TestNotifier();
        $testLogger = new TestLogger();
        $multiNotifier = new MultiNotifier();
        $multiNotifier->addNotifier($testNotifier);

        $filter = new Filter([]);

        $termin = new Termin(new ScraperLocator(['berlin_services' => $mockScraper]), $testLogger, $multiNotifier, $filter, $mockConfig);

        $termin->run([new Site('hello', 'berlin_services', [])]);

        self::assertEmpty($testNotifier->getNotifications());
        self::assertTrue($testLogger->hasInfoThatContains('No availability found for: hello'));
    }
}
