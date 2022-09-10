<?php

declare(strict_types=1);

namespace Inverse\Termin;

use Inverse\Termin\Config\Config;
use Inverse\Termin\Config\Site;
use Inverse\Termin\Notifier\MultiNotifier;
use Psr\Log\LoggerInterface;

class Termin
{
    private Scraper $scraper;

    private LoggerInterface $logger;

    private MultiNotifier $notifier;

    private Filter $filter;

    private Config $config;

    public function __construct(
        Scraper $scraper,
        LoggerInterface $logger,
        MultiNotifier $notifier,
        Filter $filter,
        Config $config
    ) {
        $this->scraper = $scraper;
        $this->logger = $logger;
        $this->notifier = $notifier;
        $this->filter = $filter;
        $this->config = $config;
    }

    /**
     * @param Site[] $sites
     */
    public function run(array $sites): void
    {
        $this->logger->info(sprintf('Starting to run [sites: %d, notifiers: %d]', count($sites), $this->notifier->registeredNotifierCount()));

        foreach ($sites as $site) {
            $results = $this->scraper->scrapeSite($site->getUrl());

            if (empty($results)) {
                $this->logger->info('No availability found for: '.$site->getLabel());

                continue;
            }

            $results = array_unique($results);
            $results = $this->filter->applyRules($results);

            foreach ($results as $result) {
                $this->logger->info(
                    sprintf('Found availability for %s @ %s', $site->getLabel(), $result->getDateTime()->format('c'))
                );

                $this->notifier->notify($site->getLabel(), $site->getUrl(), $result->getDateTime());

                if (count($results) > 1 && !$this->config->isAllowMultipleNotifications()) {
                    break;
                }
            }
        }
    }
}
