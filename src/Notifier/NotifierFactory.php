<?php

declare(strict_types=1);

namespace Inverse\Termin\Notifier;

use Inverse\Termin\Config\Config;
use Inverse\Termin\Notify\MultiNotifier;
use Inverse\Termin\Notify\NotifyInterface;
use Inverse\Termin\Notify\PushbulletNotifier;
use Inverse\Termin\Notify\TelegramNotifier;
use Pushbullet\Pushbullet;
use TelegramBot\Api\BotApi;

class NotifierFactory
{
    public static function create(Config $config): NotifyInterface
    {
        $multiNotifier = new MultiNotifier();
        if (null !== $config->getPushbullet()) {
            $pushbullet = new Pushbullet($config->getPushbullet()->getApiToken());
            $pushBulletNotifier = new PushbulletNotifier($pushbullet);
            $multiNotifier->addNotifier($pushBulletNotifier);
        }

        if (null !== $config->getTelegram()) {
            $botApi = new BotApi($config->getTelegram()->getApiKey());
            $telegramNotifier = new TelegramNotifier($botApi, $config->getTelegram()->getChatId());
            $multiNotifier->addNotifier($telegramNotifier);
        }

        return $multiNotifier;
    }
}
