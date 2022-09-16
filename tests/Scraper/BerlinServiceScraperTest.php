<?php

declare(strict_types=1);

namespace Tests\Inverse\Termin\Scraper;

use DateTimeInterface;
use Inverse\Termin\HttpClient\HttpClientFactoryInterface;
use Inverse\Termin\Scraper\BerlinServiceScraper;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Tests\Inverse\Termin\TestUtils;

class BerlinServiceScraperTest extends TestCase
{
    public function testScrapeSiteNoAppointments(): void
    {
        $mockHttpClientFactory = new MockHttpClientFactory(
            [
                new MockResponse(TestUtils::loadFixture('mock_response_no_termin.html')),
                new MockResponse(TestUtils::loadFixture('mock_response_next_no_termin.html')),
            ]
        );

        $scraper = new BerlinServiceScraper($mockHttpClientFactory->create(), new NullLogger());

        self::assertEmpty($scraper->scrapeSite('https://service.berlin.de/terminvereinbarung/termin/day/'));
    }

    public function testScrapeSiteNextAppointment(): void
    {
        $mockHttpClientFactory = new MockHttpClientFactory(
            [
                new MockResponse(TestUtils::loadFixture('mock_response_no_termin.html')),
                new MockResponse(TestUtils::loadFixture('mock_response_next_termin.html')),
            ]
        );

        $scraper = new BerlinServiceScraper($mockHttpClientFactory->create(), new NullLogger());

        $results = $scraper->scrapeSite('https://service.berlin.de/terminvereinbarung/termin/day/');
        self::assertNotEmpty($results);
        self::assertEquals('2020-11-15T00:00:00+01:00', $results[0]->getDateTime()->format(DateTimeInterface::ATOM));
    }

    public function testScrapeSiteOneAppointment(): void
    {
        $mockHttpClientFactory = new MockHttpClientFactory(
            [
                new MockResponse(TestUtils::loadFixture('mock_response_one_termin.html')),
                new MockResponse(TestUtils::loadFixture('mock_response_next_no_termin.html')),
            ]
        );

        $scraper = new BerlinServiceScraper($mockHttpClientFactory->create(), new NullLogger());

        $results = $scraper->scrapeSite('https://service.berlin.de/terminvereinbarung/termin/day/');
        self::assertNotEmpty($results);
        self::assertEquals('2020-09-15T00:00:00+02:00', $results[0]->getDateTime()->format(DateTimeInterface::ATOM));
    }

    public function testScrapeSiteMultiAppointment(): void
    {
        $mockHttpClientFactory = new MockHttpClientFactory(
            [
                new MockResponse(TestUtils::loadFixture('mock_response_multi_termin.html')),
                new MockResponse(TestUtils::loadFixture('mock_response_next_no_termin.html')),
            ]
        );

        $scraper = new BerlinServiceScraper($mockHttpClientFactory->create(), new NullLogger());

        $results = $scraper->scrapeSite('https://service.berlin.de/terminvereinbarung/termin/day/');
        self::assertCount(4, $results);
    }

    public function testSupportedDomains(): void
    {
        self::assertEquals([
            'https://service.berlin.de',
        ], (new BerlinServiceScraper((new MockHttpClientFactory([]))->create(), new NullLogger()))->supportsDomains());
    }
}

class MockHttpClientFactory implements HttpClientFactoryInterface
{
    /**
     * @var MockResponse[]
     */
    private array $responses;

    public function __construct(array $responses)
    {
        $this->responses = $responses;
    }

    public function create(): HttpClientInterface
    {
        return new MockHttpClient($this->responses);
    }
}
