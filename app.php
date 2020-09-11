<?php

use Inverse\Termin\Container;

require __DIR__.'/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();

$container = new Container();

$siteParser = $container->getSiteParser();

$sites = $siteParser->parse(getenv('SITES'));

$scraper = $container->getScraper();
$logger = $container->getLogger();
$notifier = $container->getNotifier();

foreach ($sites as $site) {
    $results = $scraper->scrapeSite($site->getUrl());

    foreach ($results as $result) {
        if ($result->isFound()) {
            $logger->info(
                sprintf('Found availability for %s @ %s', $site->getLabel(), $result->getDate()->format('c'))
            );

            $notifier->notify($site->getLabel(), $site->getUrl(), $result->getDate());
        }
    }

    $logger->info('No availability found for: '.$site->getLabel());
}
