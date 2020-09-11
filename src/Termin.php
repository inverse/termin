<?php

declare(strict_types=1);

namespace Inverse\Termin;

use Inverse\Termin\Notify\NotifyInterface;
use Monolog\Logger;

class Termin
{
    /**
     * @var Scraper
     */
    private $scraper;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var NotifyInterface
     */
    private $notifier;

    public function __construct(Scraper $scraper, Logger $logger, NotifyInterface $notifier)
    {
        $this->scraper = $scraper;
        $this->logger = $logger;
        $this->notifier = $notifier;
    }

    public function run(array $sites): void
    {
        foreach ($sites as $site) {
            $results = $this->scraper->scrapeSite($site->getUrl());

            foreach ($results as $result) {
                if ($result->isFound()) {
                    $this->logger->info(
                        sprintf('Found availability for %s @ %s', $site->getLabel(), $result->getDate()->format('c'))
                    );

                    $this->notifier->notify($site->getLabel(), $site->getUrl(), $result->getDate());
                }
            }

            $this->logger->info('No availability found for: '.$site->getLabel());
        }
    }
}
