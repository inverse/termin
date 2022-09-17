<?php

declare(strict_types=1);

namespace Tests\Inverse\Termin\Scraper;

use Inverse\Termin\Exceptions\TerminException;
use Inverse\Termin\Scraper\ScraperInterface;
use Inverse\Termin\Scraper\ScraperLocator;
use PHPUnit\Framework\TestCase;

class ScraperLocatorTest extends TestCase
{
    public function testLocateEmpty(): void
    {
        $this->expectException(TerminException::class);
        $this->expectExceptionMessage("Unable to locate scraper for 'berlin_service'");
        $scraperLocator = new ScraperLocator([]);
        $scraperLocator->locate('berlin_service');
    }

    public function testLocateNoMatch(): void
    {
        $this->expectException(TerminException::class);
        $this->expectExceptionMessage("Unable to locate scraper for 'berlin_service'");

        $mockScraper = $this->createMock(ScraperInterface::class);

        $scraperLocator = new ScraperLocator(['berlin_foreigners_registration_office' => $mockScraper]);
        $scraperLocator->locate('berlin_service');
    }

    public function testLocateMatch(): void
    {
        $mockScraper = $this->createMock(ScraperInterface::class);
        $scraperLocator = new ScraperLocator(['berlin_service' => $mockScraper]);
        self::assertInstanceOf(ScraperInterface::class, $scraperLocator->locate('berlin_service'));
    }
}
