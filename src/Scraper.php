<?php

namespace Inverse\Termin;

use Goutte\Client;
use Pushbullet\Pushbullet;

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
        $crawler = $crawler->filter('.calendar-table table tr td');

        foreach ($crawler as $node) {
            $class = $node->getAttribute('class');
            $classes = explode(' ', $class);

            if (in_array('buchbar', $classes)) {
                $this->notify($name, $url);
                break;
            }
        }
    }

    private function notify(string $name, string $url)
    {
        $title = 'Appointment Found';
        $body = sprintf('%s appointment found', $name);
        $this->pushbullet->allDevices()->pushLink($title, $url, $body);
    }
}