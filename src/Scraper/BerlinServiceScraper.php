<?php

declare(strict_types=1);

namespace Inverse\Termin\Scraper;

use Inverse\Termin\Config\Site;
use Inverse\Termin\DateHelper;
use Inverse\Termin\Exceptions\TerminException;
use Inverse\Termin\Result;
use Psr\Log\LoggerInterface;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class BerlinServiceScraper implements ScraperInterface
{
    private HttpClientInterface $httpClient;

    private LoggerInterface $logger;

    public function __construct(
        HttpClientInterface $httpClient,
        LoggerInterface $logger
    ) {
        $this->httpClient = $httpClient;
        $this->logger = $logger;
    }

    /**
     * @return Result[]
     *
     * @throws TerminException
     */
    public function scrape(Site $site): array
    {
        if (!array_key_exists('url', $site->getParams())) {
            throw new TerminException(sprintf("Site of type '%s' missing param with key url", $site->getType()));
        }

        return $this->scrapeSite($site->getParams()['url'], $site);
    }

    private function scrapeSite(string $url, Site $site): array
    {
        $this->logger->debug(sprintf('GET %s', $url));

        $browser = new HttpBrowser($this->httpClient);
        $crawler = $browser->request('GET', $url);

        $results = [];

        $nextUrl = $this->extractNextUrl($crawler);
        if (null !== $nextUrl) {
            $results = array_merge($results, $this->scrapeSite($nextUrl, $site));
        }

        $crawler = $crawler->filter('td.buchbar');

        foreach ($crawler as $element) {
            $results[] = $this->processAvailable($element, $url, $site);
        }

        return $results;
    }

    private function processAvailable(\DOMNode $element, string $url, Site $site): Result
    {
        $crawler = new Crawler($element, $url);
        $title = $crawler->filter('a')->extract(['title']);
        $dateTime = DateHelper::createDateTime(explode(' ', $title[0])[0]);

        return new Result($site->getParams()['url'], $site->getLabel(), $dateTime);
    }

    private function extractNextUrl(Crawler $crawler): ?string
    {
        $anchor = $crawler->filter('th.next a')->getNode(0);

        if (!$anchor) {
            return null;
        }

        return $crawler->filter('th.next a')->first()->link()->getUri();
    }
}
