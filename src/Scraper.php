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
    private const CLASS_UNAVAILABLE = 'nichtbuchbar';

    /**
     * @var Client
     */
    private $client;

    /**
     * @var bool
     */
    private $collectMultiple;

    public function __construct(HttpClientInterface $httpClient, bool $collectMultiple = false)
    {
        $this->client = new Client($httpClient);
        $this->collectMultiple = $collectMultiple;
    }

    public function scrapeSite(string $url): array
    {
        $crawler = $this->client->request('GET', $url);
        $crawler = $crawler->filter('.calendar-table table');

        $results = [];

        foreach ($crawler as $element) {
            $results = array_merge($results, $this->processMonth($element));

            if (!empty($results) && !$this->collectMultiple) {
                break;
            }
        }

        return $results;
    }

    private function processMonth(DOMNode $element): array
    {
        $crawler = new Crawler($element);
        $monthStr = trim($crawler->filter('.month')->text());
        $crawler = $crawler->filter('tr td');

        $results = [];

        /** @var DOMElement $node */
        foreach ($crawler as $node) {
            $class = $node->getAttribute('class');
            $classes = explode(' ', $class);

            if (in_array(self::CLASS_AVAILABLE, $classes)) {
                $dateTime = DateHelper::createDateTime($node->textContent, DateHelper::monthConvert($monthStr));

                if (isset($dateTime)) {
                    $results[] = Result::createFound($dateTime);

                    if (!$this->collectMultiple) {
                        break;
                    }
                }
            }
        }

        return $results;
    }
}
