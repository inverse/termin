<?php

namespace Inverse\Termin;

use DateTime;
use DateTimeZone;
use DOMElement;
use DOMNode;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

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
        $this->client = new Client();
        $this->client->setHeader('User-Agent', self::USER_AGENT);
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

                $dateTime = $this->createDateTime($node->textContent, $this->monthConvert($monthStr));

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

    private function createDateTime(string $day, string $month): ?DateTime
    {
        $dateTime = null;

        if (empty($day) || empty($month)) {
            return $dateTime;
        }

        try {
            $dateTime = new DateTime(sprintf('%s %s', $day, $month), new DateTimeZone('Europe/Berlin'));
        } finally {
            return $dateTime;
        }
    }

    private function monthConvert(string $monthStr): string
    {
        $mapper = ['Januar' => 'January',
            'Februar' => 'February',
            'März' => 'March',
            'April' => 'April',
            'Mai' => 'May',
            'Juni' => 'June',
            'Juli' => 'July',
            'August' => 'August',
            'September' => 'September',
            'Oktober' => 'October',
            'November' => 'November',
            'Dezember' => 'December'
        ];

        foreach ($mapper as $month => $replace) {
            if (strpos($monthStr, $month) !== false) {
                return str_replace($month, $replace, $monthStr);
            }
        }

        return $monthStr;
    }
}
