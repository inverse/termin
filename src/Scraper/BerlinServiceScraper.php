<?php

declare(strict_types=1);

namespace Inverse\Termin\Scraper;

use DOMElement;
use DOMNode;
use Goutte\Client;
use Inverse\Termin\Config\Site;
use Inverse\Termin\DateHelper;
use Inverse\Termin\Exceptions\TerminException;
use Inverse\Termin\Result;
use Psr\Log\LoggerInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class BerlinServiceScraper implements ScraperInterface
{
    private const CLASS_AVAILABLE = 'buchbar';

    private Client $client;

    private LoggerInterface $logger;

    public function __construct(
        HttpClientInterface $httpClient,
        LoggerInterface $logger
    ) {
        $this->client = new Client($httpClient);
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

        $crawler = $this->client->request('GET', $url);

        $crawler = $crawler->filter('.calendar-table table');

        $results = [];
        foreach ($crawler as $element) {
            $results[] = $this->processMonth($element, $url, $site);
        }

        return array_merge([], ...$results);
    }

    private function processMonth(DOMNode $element, string $url, Site $site): array
    {
        $crawler = new Crawler($element, $url);
        $monthStr = trim($crawler->filter('.month')->text());
        $nextUrl = $this->extractNextUrl($crawler);
        $crawler = $crawler->filter('tr td');
        $results = [];

        /** @var DOMElement $node */
        foreach ($crawler as $node) {
            $class = $node->getAttribute('class');
            $classes = explode(' ', $class);

            if (in_array(self::CLASS_AVAILABLE, $classes, true)) {
                $dateTime = DateHelper::createDateTime($node->textContent, DateHelper::monthConvert($monthStr));

                if (isset($dateTime)) {
                    $results[] = new Result($site->getParams()['url'], $site->getLabel(), $dateTime);
                }
            }
        }

        if (null !== $nextUrl) {
            $results = array_merge($results, $this->scrapeSite($nextUrl, $site));
        }

        return $results;
    }

    private function extractNextUrl(Crawler $crawler): ?string
    {
        $anchor = $crawler->filter('th.next a')->getNode(0);

        if (!$anchor) {
            return null;
        }

        return $crawler->filter('th a')->first()->link()->getUri();
    }
}
