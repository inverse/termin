<?php

declare(strict_types=1);

namespace Tests\Inverse\Termin;

use DateTime;
use Inverse\Termin\Config\Site;
use Inverse\Termin\Filter;
use Inverse\Termin\Notifier\MultiNotifier;
use Inverse\Termin\Result;
use Inverse\Termin\Scraper;
use Inverse\Termin\Termin;
use PHPUnit\Framework\TestCase;
use Psr\Log\Test\TestLogger;
use Tests\Inverse\Termin\Notifier\TestNotifier;

class TerminTest extends TestCase
{
    public function testMatchFound(): void
    {
        $mockScraper = $this->createMock(Scraper::class);

        $mockScraper->method('scrapeSite')
            ->willReturn([new Result(new DateTime('2020-01-01 00:00:00'))])
        ;

        $testLogger = new TestLogger();
        $testNotifier = new TestNotifier();
        $multiNotifier = new MultiNotifier();
        $multiNotifier->addNotifier($testNotifier);

        $filter = new Filter([]);

        $termin = new Termin($mockScraper, $testLogger, $multiNotifier, $filter);

        $termin->run([new Site('hello', 'https://hello.com')]);

        self::assertNotEmpty($testNotifier->getNotifications());
        self::assertTrue($testLogger->hasInfoThatContains('Found availability for hello @ 2020-01-01T00:00:00+00:00'));
    }

    public function testMatchNotFound(): void
    {
        $mockScraper = $this->createMock(Scraper::class);

        $mockScraper->method('scrapeSite')
            ->willReturn([])
        ;

        $testNotifier = new TestNotifier();
        $testLogger = new TestLogger();
        $multiNotifier = new MultiNotifier();
        $multiNotifier->addNotifier($testNotifier);

        $filter = new Filter([]);

        $termin = new Termin($mockScraper, $testLogger, $multiNotifier, $filter);

        $termin->run([new Site('hello', 'https://hello.com')]);

        self::assertEmpty($testNotifier->getNotifications());
        self::assertTrue($testLogger->hasInfoThatContains('No availability found for: hello'));
    }
}
