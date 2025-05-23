<?php

declare(strict_types=1);

namespace Inverse\Termin;

use Inverse\Termin\Config\Config;
use Inverse\Termin\HttpClient\HttpClientFactory;
use Inverse\Termin\Notifier\MultiNotifier;
use Inverse\Termin\Notifier\NotifierFactory;
use Inverse\Termin\Notifier\NotifierInterface;
use Inverse\Termin\Scraper\BerlinServiceScraper;
use Inverse\Termin\Scraper\ScraperLocator;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Pimple\Container as Pimple;
use Psr\Log\LoggerInterface;

class Container extends Pimple
{
    private const ROOT_DIR = __DIR__.'/../';

    public function __construct(Config $config, Runtime $runtime = Runtime::NORMAL)
    {
        parent::__construct();

        $this[NotifierInterface::class] = static fn () => (new NotifierFactory(new MultiNotifier()))->create($config);

        $this[LoggerInterface::class] = static function () use ($config, $runtime) {
            $logger = new Logger('termin');
            if ($config->getLogToFile() || Runtime::SERVERLESS !== $runtime) {
                $logger->pushHandler(new StreamHandler(self::ROOT_DIR.'var/log/app.log', $config->getLogLevel()));
            }
            $logger->pushHandler(new StreamHandler('php://stdout', $config->getLogLevel()));

            return $logger;
        };

        $this[BerlinServiceScraper::class] = static function (self $container) {
            $httpClientFactory = new HttpClientFactory();

            return new BerlinServiceScraper(
                $httpClientFactory->create(),
                $container[LoggerInterface::class]
            );
        };

        $this[ScraperLocator::class] = static fn (self $container) => new ScraperLocator([
            'berlin_service' => $container[BerlinServiceScraper::class],
        ]);

        $this[Filter::class] = static fn () => new Filter($config->getRules());

        $this[Termin::class] = static fn (self $container) => new Termin(
            $container[ScraperLocator::class],
            $container[LoggerInterface::class],
            $container[NotifierInterface::class],
            $container[Filter::class],
            $config
        );
    }

    public function getTermin(): Termin
    {
        return $this[Termin::class];
    }
}
