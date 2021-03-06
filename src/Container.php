<?php

declare(strict_types=1);

namespace Inverse\Termin;

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

    public function __construct()
    {
        parent::__construct();

        $pushBulletApiToken = $this->getEnv('PUSHBULLET_API_TOKEN');
        if (!empty($pushBulletApiToken) && is_string($pushBulletApiToken)) {
            $this[PushbulletNotifier::class] = function () use ($pushBulletApiToken) {
                $pushBullet = new Pushbullet($pushBulletApiToken);

                return new PushbulletNotifier($pushBullet);
            };
        }

        $telegramApiKey = $this->getEnv('TELEGRAM_API_KEY');
        $telegramChatId = $this->getEnv('TELEGRAM_CHAT_ID');
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
            $logger = new Logger('termin');
            $logger->pushHandler(new StreamHandler(self::ROOT_DIR.'var/log/app.log', Logger::INFO));
            $logger->pushHandler(new StreamHandler('php://stdout', Logger::INFO));

            return $logger;
        };

        $allowMultipleNotifications = (bool) $this->getEnv('ALLOW_MULTIPLE_NOTIFICATIONS');

        $this[Scraper::class] = function () use ($allowMultipleNotifications) {
            $httpClientFactory = new HttpClientFactory();

            return new Scraper($httpClientFactory->create(), $allowMultipleNotifications);
        };

        $this[SiteParser::class] = function () {
            return new SiteParser();
        };

        $this[Termin::class] = function (self $container) {
            return new Termin(
                $container[Scraper::class],
                $container[LoggerInterface::class],
                $container[NotifyInterface::class]
            );
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

    public function getTermin(): Termin
    {
        return $this[Termin::class];
    }

    private function getEnv(string $key, string $default = ''): string
    {
        if (array_key_exists($key, $_ENV)) {
            return $_ENV[$key];
        }

        return $default;
    }
}
