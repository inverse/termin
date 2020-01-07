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
     * @var Client
     */
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function scrapeSite(string $url): Result
    {
        $crawler = $this->client->request('GET', $url);
        $crawler = $crawler->filter('.calendar-table table');

        $result = Result::createNotFound();

        foreach ($crawler as $element) {
            $result = $this->processMonth($element);

            if ($result->isFound()) {
                break;
            }
        }

        return $result;
    }

    private function processMonth(DOMNode $element): Result
    {
        $crawler = new Crawler($element);
        $monthStr = trim($crawler->filter('.month')->text());
        $crawler = $crawler->filter('tr td');

        /** @var DOMElement $node */
        foreach ($crawler as $node) {
            $class = $node->getAttribute('class');
            $classes = explode(' ', $class);

            if (in_array('nichtbuchbar', $classes)) {
                $date = sprintf('%s %s', $node->textContent, $this->monthConvert($monthStr));

                return Result::createFound(new DateTime($date, new DateTimeZone('Europe/Berlin')));
            }
        }

        return Result::createNotFound();
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
