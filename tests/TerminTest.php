<?php

declare(strict_types=1);

namespace Tests\Inverse\Termin;

use DateTime;
use Inverse\Termin\Result;
use Inverse\Termin\Scraper;
use Inverse\Termin\Site;
use Inverse\Termin\Termin;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Tests\Inverse\Termin\Notify\TestNotifier;

class TerminTest extends TestCase
{
    public function testMatchFound(): void
    {
        $mockScraper = $this->createMock(Scraper::class);

        $mockScraper->method('scrapeSite')
            ->willReturn([Result::createFound(new DateTime())])
        ;

        $mockLogger = $this->createMock(Logger::class);

        $testNotifier = new TestNotifier();

        $termin = new Termin($mockScraper, $mockLogger, $testNotifier);

        $termin->run([new Site('hello', 'https://hello.com')]);

        self::assertNotEmpty($testNotifier->getNotifications());
    }

    public function testMatchNotFound(): void
    {
        $mockScraper = $this->createMock(Scraper::class);

        $mockScraper->method('scrapeSite')
            ->willReturn([])
        ;

        $mockLogger = $this->createMock(Logger::class);

        $testNotifier = new TestNotifier();

        $termin = new Termin($mockScraper, $mockLogger, $testNotifier);

        $termin->run([new Site('hello', 'https://hello.com')]);

        self::assertEmpty($testNotifier->getNotifications());
    }
}
