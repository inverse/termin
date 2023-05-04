<?php

declare(strict_types=1);

namespace Tests\Inverse\Termin\Scraper;

use Inverse\Termin\Config\Site;
use Inverse\Termin\Exceptions\TerminException;
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
    public function testScrapeSiteParamsMissingUrl(): void
    {
        self::expectException(TerminException::class);
        self::expectExceptionMessage("Site of type 'berlin_service' missing param with key url");
        $mockHttpClientFactory = new MockHttpClientFactory([]);
        $scraper = new BerlinServiceScraper($mockHttpClientFactory->create(), new NullLogger());

        self::assertEmpty(
            $scraper->scrape(
                new Site(
                    'Yolo',
                    'berlin_service',
                    []
                )
            )
        );
    }

    public function testScrapeSiteNoAppointments(): void
    {
        $mockHttpClientFactory = new MockHttpClientFactory(
            [
                new MockResponse(TestUtils::loadFixture('mock_response_no_termin.html')),
                new MockResponse(TestUtils::loadFixture('mock_response_next_no_termin.html')),
            ]
        );

        $scraper = new BerlinServiceScraper($mockHttpClientFactory->create(), new NullLogger());

        self::assertEmpty(
            $scraper->scrape(
                new Site(
                    'Yolo',
                    'berlin_service',
                    [
                        'url' => 'https://service.berlin.de/terminvereinbarung/termin/day/',
                    ]
                )
            )
        );
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

        $results = $scraper->scrape(new Site(
            'Yolo',
            'berlin_service',
            ['url' => 'https://service.berlin.de/terminvereinbarung/termin/day/',
            ]
        ));
        self::assertNotEmpty($results);
        self::assertEquals('2023-06-29T00:00:00+02:00', $results[0]->getDateTime()->format(\DateTimeInterface::ATOM));
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

        $results = $scraper->scrape(new Site(
            'Yolo',
            'berlin_service',
            ['url' => 'https://service.berlin.de/terminvereinbarung/termin/day/',
            ]
        ));
        self::assertNotEmpty($results);
        self::assertEquals('2023-06-29T00:00:00+02:00', $results[0]->getDateTime()->format(\DateTimeInterface::ATOM));
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

        $results = $scraper->scrape(new Site(
            'Yolo',
            'berlin_service',
            ['url' => 'https://service.berlin.de/terminvereinbarung/termin/day/',
            ]
        ));
        self::assertCount(4, $results);
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
