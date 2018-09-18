<?php

namespace Inverse\Termin;

use DateTime;
use DOMElement;
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

    public function scrapeSite(string $name, string $url): Result
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

    private function processMonth(DOMElement $element): Result
    {
        $crawler = new Crawler($element);
        $month = trim($crawler->filter('.month')->text());
        $crawler = $crawler->filter('tr td');

        foreach ($crawler as $node) {
            $class = $node->getAttribute('class');
            $classes = explode(' ', $class);

            if (in_array('buchbar', $classes)) {
                $date = sprintf('%s %s', $node->textContent, $month);

                return Result::createFound(new DateTime($date));
            }
        }

        return Result::createNotFound();
    }
}
