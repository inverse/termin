<?php

declare(strict_types=1);

namespace Inverse\Termin;

use DOMElement;
use DOMNode;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Scraper
{
    private const CLASS_AVAILABLE = 'buchbar';

    private Client $client;

    private bool $collectMultiple;

    public function __construct(HttpClientInterface $httpClient, bool $collectMultiple = false)
    {
        $this->client = new Client($httpClient);
        $this->collectMultiple = $collectMultiple;
    }

    /**
     * @return Result[]
     */
    public function scrapeSite(string $url): array
    {
        $crawler = $this->client->request('GET', $url);
        $crawler = $crawler->filter('.calendar-table table');

        $results = [];

        foreach ($crawler as $element) {
            $results = array_merge($results, $this->processMonth($element, $url));

            if (!empty($results) && !$this->collectMultiple) {
                break;
            }
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

                    if (!$this->collectMultiple) {
                        break;
                    }
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
