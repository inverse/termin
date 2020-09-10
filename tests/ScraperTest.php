<?php

declare(strict_types=1);

namespace Tests\Inverse\Termin;

use DateTimeInterface;
use Exception;
use Inverse\Termin\HttpClient\HttpClientFactoryInterface;
use Inverse\Termin\Scraper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ScraperTest extends TestCase
{
    public function testScrapeSiteNoAppointments(): void
    {
        $mockHttpClientFactory = new MockHttpClientFactory(
            [
                new MockResponse($this->loadFixture('mock_response_no_termin.html')),
            ]
        );

        $scraper = new Scraper($mockHttpClientFactory->create());

        self::assertEmpty($scraper->scrapeSite('https://service.berlin.de/terminvereinbarung/termin/day/'));
    }

    public function testScrapeSiteOneAppointment(): void
    {
        $mockHttpClientFactory = new MockHttpClientFactory(
            [
                new MockResponse($this->loadFixture('mock_response_one_termin.html')),
            ]
        );

        $scraper = new Scraper($mockHttpClientFactory->create());

        $results = $scraper->scrapeSite('https://service.berlin.de/terminvereinbarung/termin/day/');
        self::assertEquals('2020-09-15T00:00:00+02:00', $results[0]->getDate()->format(DateTimeInterface::ATOM));
    }

    private function loadFixture(string $name): string
    {
        $fixturePath = __DIR__.'/Fixtures/'.$name;
        $contents = file_get_contents($fixturePath);

        if (false === $contents) {
            throw new Exception(sprintf('Unable to load fixture: %s', $fixturePath));
        }

        return $contents;
    }
}

class MockHttpClientFactory implements HttpClientFactoryInterface
{
    /**
     * @var MockResponse[]
     */
    private $responses;

    public function __construct(array $responses)
    {
        $this->responses = $responses;
    }

    public function create(): HttpClientInterface
    {
        return new MockHttpClient($this->responses);
    }
}
