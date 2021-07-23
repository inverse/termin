<?php

declare(strict_types=1);

namespace Inverse\Termin;

use Inverse\Termin\Config\Site;
use Inverse\Termin\Notify\NotifyInterface;
use Psr\Log\LoggerInterface;

class Termin
{
    private Scraper $scraper;

    private LoggerInterface $logger;

    private NotifyInterface $notifier;

    public function __construct(Scraper $scraper, LoggerInterface $logger, NotifyInterface $notifier)
    {
        $this->scraper = $scraper;
        $this->logger = $logger;
        $this->notifier = $notifier;
    }

    /**
     * @param Site[] $sites
     */
    public function run(array $sites): void
    {
        foreach ($sites as $site) {
            $results = $this->scraper->scrapeSite($site->getUrl());

            if (empty($results)) {
                $this->logger->info('No availability found for: '.$site->getLabel());

                continue;
            }

            foreach ($results as $result) {
                $this->logger->info(
                    sprintf('Found availability for %s @ %s', $site->getLabel(), $result->getDate()->format('c'))
                );

                $this->notifier->notify($site->getLabel(), $site->getUrl(), $result->getDate());
            }
        }
    }
}
