<?php

namespace Inverse\Termin;

use DOMElement;
use Goutte\Client;
use Pushbullet\Pushbullet;
use Symfony\Component\DomCrawler\Crawler;

class Scraper
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var Pushbullet
     */
    private $pushbullet;

    public function __construct(string $pushbulletApiToken)
    {
        $this->client = new Client();
        $this->pushbullet = new Pushbullet($pushbulletApiToken);
    }


    public function scrape(array $sites)
    {
        foreach ($sites as $name => $url) {
            $this->scrapeSite($name, $url);
        }
    }

    public function scrapeSite(string $name, string $url)
    {
        $crawler = $this->client->request('GET', $url);
        $crawler = $crawler->filter('.calendar-table table');

        foreach ($crawler as $element) {
            if ($this->processMonth($element, $name, $url)) {
                break;
            }
        }
    }

    private function processMonth(DOMElement $element, string $name, string $url): bool
    {
        $crawler = new Crawler($element);
        $month = trim($crawler->filter('.month')->text());
        $crawler = $crawler->filter('tr td');

        foreach ($crawler as $node) {
            $class = $node->getAttribute('class');
            $classes = explode(' ', $class);

            if (in_array('buchbar', $classes)) {
                $date = sprintf('%s %s', $node->textContent, $month);
                $this->notify($name, $url, $date);

                return true;
            }
        }

        return false;
    }

    private function notify(string $name, string $url, string $date)
    {
        $title = 'Appointment Found';
        $body = sprintf('%s appointment found for %s', $name, $date);
        $this->pushbullet->allDevices()->pushLink($title, $url, $body);
    }
}