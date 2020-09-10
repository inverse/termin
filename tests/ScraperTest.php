<?php declare(strict_types=1);

namespace Tests\Inverse\Termin;

use Inverse\Termin\HttpClient\HttpClientFactoryInterface;
use Inverse\Termin\Scraper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ScraperTest extends TestCase
{
    public function testScrapeSiteNoAppointments()
    {
        $mockHttpClientFactory = new MockHttpClientFactory([
            new MockResponse($this->loadFixture('mock_response_no_termin.html'))
        ]);

        $scraper = new Scraper($mockHttpClientFactory->create());

        self::assertEmpty($scraper->scrapeSite('https://service.berlin.de/terminvereinbarung/termin/day/'));
    }

    private function loadFixture(string $name): string
    {
        return file_get_contents(__DIR__.'/Fixtures/'.$name);
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
