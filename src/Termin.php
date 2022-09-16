<?php

declare(strict_types=1);

namespace Inverse\Termin;

use Inverse\Termin\Config\Config;
use Inverse\Termin\Config\Site;
use Inverse\Termin\Notifier\MultiNotifier;
use Inverse\Termin\Scraper\ScraperLocator;
use Psr\Log\LoggerInterface;

class Termin
{
    private ScraperLocator $scraperLocator;

    private LoggerInterface $logger;

    private MultiNotifier $notifier;

    private Filter $filter;

    private Config $config;

    public function __construct(
        ScraperLocator $scraperLocator,
        LoggerInterface $logger,
        MultiNotifier $notifier,
        Filter $filter,
        Config $config
    ) {
        $this->scraperLocator = $scraperLocator;
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
            $scraper = $this->scraperLocator->locate($site->getType());
            $results = $scraper->scrape($site);

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

                $this->notifier->notify($result->getLabel(), $result->getUrl(), $result->getDateTime());

                if (count($results) > 1 && !$this->config->isAllowMultipleNotifications()) {
                    $this->logger->debug(
                        sprintf('%s results found, skipping due to allow_multiple_notifications being false', count($results))
                    );

                    break;
                }
            }
        }
    }
}
