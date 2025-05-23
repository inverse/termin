<?php

declare(strict_types=1);

namespace Inverse\Termin;

use Inverse\Termin\Config\Config;
use Inverse\Termin\Config\Site;
use Inverse\Termin\Exceptions\TerminException;
use Inverse\Termin\Notifier\MultiNotifier;
use Inverse\Termin\Scraper\ScraperLocator;
use Psr\Log\LoggerInterface;

class Termin
{
    public function __construct(
        private readonly ScraperLocator $scraperLocator,
        private readonly LoggerInterface $logger,
        private readonly MultiNotifier $notifier,
        private readonly Filter $filter,
        private readonly Config $config
    ) {}

    /**
     * @param Site[] $sites
     */
    public function run(array $sites): void
    {
        $this->logger->info(sprintf('Starting to run [sites: %d, notifiers: %d]', count($sites), $this->notifier->registeredNotifierCount()));
        foreach ($sites as $site) {
            try {
                $this->processSite($site);
            } catch (TerminException $exception) {
                $this->logger->error(sprintf('Failed to process %s: %s', $site->getLabel(), $exception->getMessage()));
            }
        }
    }

    /**
     * @throws TerminException
     */
    private function processSite(Site $site): void
    {
        $scraper = $this->scraperLocator->locate($site->getType());
        $this->logger->debug(sprintf('Using %s for %s', $scraper::class, $site->getLabel()));
        $results = $scraper->scrape($site);

        if (empty($results)) {
            $this->logger->info('No availability found for: '.$site->getLabel());

            return;
        }

        $results = array_unique($results);
        $results = $this->filter->applyRules($results);
        $this->processResults($site, $results);
    }

    private function processResults(Site $site, array $results): void
    {
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
