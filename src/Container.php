<?php

declare(strict_types=1);

namespace Inverse\Termin;

use Inverse\Termin\Config\Config;
use Inverse\Termin\HttpClient\HttpClientFactory;
use Inverse\Termin\Notify\MultiNotifier;
use Inverse\Termin\Notify\NotifyInterface;
use Inverse\Termin\Notify\PushbulletNotifier;
use Inverse\Termin\Notify\TelegramNotifier;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Pimple\Container as Pimple;
use Psr\Log\LoggerInterface;
use Pushbullet\Pushbullet;
use TelegramBot\Api\BotApi;

class Container extends Pimple
{
    private const ROOT_DIR = __DIR__.'/../';

    public function __construct(Config $config)
    {
        parent::__construct();

        if (null !== $config->getPushbullet()) {
            $this[PushbulletNotifier::class] = function () use ($config) {
                $pushBullet = new Pushbullet($config->getPushbullet()->getApiToken());

                return new PushbulletNotifier($pushBullet);
            };
        }

        if (null !== $config->getTelegram()) {
            $this[TelegramNotifier::class] = function () use ($config) {
                $botApi = new BotApi($config->getTelegram()->getApiKey());

                return new TelegramNotifier($botApi, $config->getTelegram()->getChatId());
            };
        }

        $this[NotifyInterface::class] = function (self $container) {
            $notifyService = new MultiNotifier();

            if (isset($container[TelegramNotifier::class])) {
                $notifyService->addNotifier($container[TelegramNotifier::class]);
            }

            if (isset($container[PushbulletNotifier::class])) {
                $notifyService->addNotifier($container[PushbulletNotifier::class]);
            }

            return $notifyService;
        };

        $this[LoggerInterface::class] = function () {
            $logger = new Logger('termin');
            $logger->pushHandler(new StreamHandler(self::ROOT_DIR.'var/log/app.log', Logger::INFO));
            $logger->pushHandler(new StreamHandler('php://stdout', Logger::INFO));

            return $logger;
        };

        $this[Scraper::class] = function () use ($config) {
            $httpClientFactory = new HttpClientFactory();

            return new Scraper($httpClientFactory->create(), $config->isAllowMultipleNotifications());
        };

        $this[Termin::class] = function (self $container) {
            return new Termin(
                $container[Scraper::class],
                $container[LoggerInterface::class],
                $container[NotifyInterface::class]
            );
        };
    }

    public function getTermin(): Termin
    {
        return $this[Termin::class];
    }
}
