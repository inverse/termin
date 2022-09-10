<?php

declare(strict_types=1);

namespace Inverse\Termin;

use DOMElement;
use DOMNode;
use Goutte\Client;
use Psr\Log\LoggerInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Scraper
{
    private const CLASS_AVAILABLE = 'buchbar';

    private Client $client;

    private LoggerInterface $logger;

    private array $visited;

    public function __construct(
        HttpClientInterface $httpClient,
        LoggerInterface $logger,
    ) {
        $this->client = new Client($httpClient);
        $this->logger = $logger;
        $this->visited = [];
    }

    /**
     * @return Result[]
     */
    public function scrapeSite(string $url): array
    {
        $this->logger->debug(sprintf('GET %s', $url));

        $crawler = $this->client->request('GET', $url);

        $contentHash = sha1($crawler->html());
        if (array_key_exists($contentHash, $this->visited)) {
            $this->logger->debug(sprintf('Already visited, skipping url:%s', $url));

            return [];
        }

        $this->visited[$contentHash] = $url;
        $crawler = $crawler->filter('.calendar-table table');

        $results = [];

        foreach ($crawler as $element) {
            $results = array_merge($results, $this->processMonth($element, $url));
        }

        return $results;
    }

    private function processMonth(DOMNode $element, string $url): array
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

            if (in_array(self::CLASS_AVAILABLE, $classes)) {
                $dateTime = DateHelper::createDateTime($node->textContent, DateHelper::monthConvert($monthStr));

                if (isset($dateTime)) {
                    $results[] = new Result($dateTime);
                }
            }
        }

        if (null !== $nextUrl) {
            $results = array_merge($results, $this->scrapeSite($nextUrl));
        }

        return $results;
    }

    private function extractNextUrl(Crawler $crawler): ?string
    {
        $anchor = $crawler->filter('th a')->getNode(0);

        if (!$anchor) {
            return null;
        }

        return $crawler->filter('th a')->first()->link()->getUri();
    }
}
