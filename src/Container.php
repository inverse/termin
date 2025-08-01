<?php

declare(strict_types=1);

namespace Inverse\Termin;

use Inverse\Termin\Config\Config;
use Inverse\Termin\HttpClient\HttpClientFactory;
use Inverse\Termin\Notifier\MultiNotifier;
use Inverse\Termin\Notifier\NotifierFactory;
use Inverse\Termin\Scraper\BerlinServiceScraper;
use Inverse\Termin\Scraper\ScraperLocator;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Pimple\Container as Pimple;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Container extends Pimple
{
    public function __construct(Config $config, Runtime $runtime = Runtime::NORMAL)
    {
        parent::__construct();

        $this[MultiNotifier::class] = static fn () => (new NotifierFactory(new MultiNotifier()))->create($config);

        $this[LoggerInterface::class] = static function () use ($config, $runtime) {
            $logger = new Logger('termin');
            $logger->pushHandler(new StreamHandler('php://stdout', $config->getLoggerConfig()->getLevel()));
            if ($config->getLoggerConfig()->getLogToFile() || Runtime::SERVERLESS !== $runtime) {
                $logger->pushHandler(new StreamHandler($config->getLoggerConfig()->getLogFileLocation(), $config->getLoggerConfig()->getLevel()));
            }

            return $logger;
        };

        $this[HttpClientInterface::class] = static fn () => (new HttpClientFactory())->create();
        $this[BerlinServiceScraper::class] = static fn (self $container) => new BerlinServiceScraper(
            $container[HttpClientInterface::class],
            $container[LoggerInterface::class]
        );

        $this[ScraperLocator::class] = static fn (self $container) => new ScraperLocator([
            'berlin_service' => $container[BerlinServiceScraper::class],
        ]);

        $this[Filter::class] = static fn () => new Filter($config->getRules());

        $this[Termin::class] = static fn (self $container) => new Termin(
            $container[ScraperLocator::class],
            $container[LoggerInterface::class],
            $container[MultiNotifier::class],
            $container[Filter::class],
            $config
        );
    }

    public function getTermin(): Termin
    {
        return $this[Termin::class];
    }
}
