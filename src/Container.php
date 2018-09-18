<?php

namespace Inverse\Termin;

use Inverse\Termin\Notify\NotifyInterface;
use Inverse\Termin\Notify\PushbulletNotifier;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Pimple\Container as Pimple;
use Psr\Log\LoggerInterface;
use Pushbullet\Pushbullet;

class Container extends Pimple
{
    public function __construct()
    {
        parent::__construct();
        $this[Pushbullet::class] = function () {
            return new Pushbullet(getenv('PUSHBULLET_API_TOKEN'));
        };

        $this[NotifyInterface::class] = function (Container $container) {
            return new PushbulletNotifier($container[Pushbullet::class]);
        };

        $this[LoggerInterface::class] = function () {
            $logger = new Logger('name');
            $logger->pushHandler(new StreamHandler(__DIR__.'../var/log/app.log', Logger::INFO));

            return $logger;
        };

        $this[Scraper::class] = function (Container $container) {
            return new Scraper($container[LoggerInterface::class], $container[NotifyInterface::class]);
        };

        $this[SiteParser::class] = function () {
            return new SiteParser();
        };
    }

    public function getScraper(): Scraper
    {
        return $this[Scraper::class];
    }

    public function getSiteParser(): SiteParser
    {
        return $this[SiteParser::class];
    }
}