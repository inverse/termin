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
        $this->expectExceptionMessage('Unable to locate scraper for https://example.com');
        $scraperLocator = new ScraperLocator([]);
        $scraperLocator->locate('https://example.com');
    }

    public function testLocateNoMatch(): void
    {
        $this->expectException(TerminException::class);
        $this->expectExceptionMessage('Unable to locate scraper for https://example.com');
        $scraperLocator = new ScraperLocator([new MockScraper(['https://foobar.com'])]);
        $scraperLocator->locate('https://example.com');
    }

    public function testLocateMatch(): void
    {
        $scraperLocator = new ScraperLocator([new MockScraper(['https://example.com'])]);
        self::assertInstanceOf(MockScraper::class, $scraperLocator->locate('https://example.com'));
    }
}

class MockScraper implements ScraperInterface
{
    private array $supportedDomains;

    public function __construct(array $supportedDomains)
    {
        $this->supportedDomains = $supportedDomains;
    }

    public function scrapeSite(string $url): array
    {
        return [];
    }

    public function supportsDomains(): array
    {
        return $this->supportedDomains;
    }
}
