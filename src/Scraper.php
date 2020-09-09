<?php

declare(strict_types=1);

namespace Inverse\Termin;

use DOMElement;
use DOMNode;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

class Scraper
{
    /**
     * Needed to by pass restrictions with setting class attributes for availability.
     */
    private const USER_AGENT = 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.101 Safari/537.36';

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

    public function __construct(bool $collectMultiple = false)
    {
        $this->client = new Client(HttpClient::create([
            'headers' => [
                'User-Agent' => self::USER_AGENT,
            ],
        ]));
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
