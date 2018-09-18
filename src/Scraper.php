<?php

namespace Inverse\Termin;

use DateTime;
use DOMElement;
use Goutte\Client;
use Inverse\Termin\Notify\NotifyInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DomCrawler\Crawler;

class Scraper
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var NotifyInterface
     */
    private $notifier;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger, NotifyInterface $notifier)
    {
        $this->client = new Client();
        $this->notifier = $notifier;
        $this->logger = $logger;
    }

    public function scrapeSite(string $name, string $url)
    {
        $crawler = $this->client->request('GET', $url);
        $crawler = $crawler->filter('.calendar-table table');

        foreach ($crawler as $element) {
            $result = $this->processMonth($element);

            if ($result->isFound()) {
                break;
            }
        }

        if (isset($result) && $result->isFound()) {
            $this->logger->info(
                sprintf('Found availability for %s @ %s', $name, $result->getDate()->format('c'))
            );

            $this->notifier->notify($name, $url, $result->getDate());

            return;
        }


        $this->logger->info('No availability found for: '.$name);

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

                return new Result(true, new DateTime($date));
            }
        }

        return new Result(false);
    }
}
