<?php

namespace Inverse\Termin;

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
    public function __construct()
    {
        parent::__construct();

        $pushBulletApiToken = getenv('PUSHBULLET_API_TOKEN');
        if (!empty($pushBulletApiToken) && is_string($pushBulletApiToken)) {
            $this[PushbulletNotifier::class] = function () use ($pushBulletApiToken) {
                $pushBullet = new Pushbullet($pushBulletApiToken);

                return new PushbulletNotifier($pushBullet);
            };
        }

        $telegramApiKey = getenv('TELEGRAM_API_KEY');
        $telegramChatId = getenv('TELEGRAM_CHAT_ID');
        if (!empty($telegramApiKey) && !empty($telegramChatId)) {
            $this[TelegramNotifier::class] = function () use ($telegramApiKey, $telegramChatId) {
                $botApi = new BotApi($telegramApiKey);

                return new TelegramNotifier($botApi, (int) $telegramChatId);
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
