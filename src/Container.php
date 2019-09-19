<?php

namespace Inverse\Termin;

use Inverse\Termin\Notify\MultiNotifier;
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

        $pushBulletApiToken = getenv('PUSHBULLET_API_TOKEN');
        if (!empty($pushBulletApiToken) && is_string($pushBulletApiToken)) {
            $this[Pushbullet::class] = function () use ($pushBulletApiToken) {
                return new Pushbullet($pushBulletApiToken);
            };
        }

        $this[NotifyInterface::class] = function (self $container) {
            $notifyService = new MultiNotifier();

            if (isset($container[Pushbullet::class])) {
                $notifyService->addNotifier(new PushbulletNotifier($container[Pushbullet::class]));
            }

            return $notifyService;
        };

        $this[LoggerInterface::class] = function () {
            $logger = new Logger('name');
            $logger->pushHandler(new StreamHandler(__DIR__.'/../var/log/app.log', Logger::INFO));

            return $logger;
        };

        $this[Scraper::class] = function () {
            return new Scraper();
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

    public function getLogger(): LoggerInterface
    {
        return $this[LoggerInterface::class];
    }

    public function getNotifier(): NotifyInterface
    {
        return $this[NotifyInterface::class];
    }
}
