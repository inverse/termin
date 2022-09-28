<?php

declare(strict_types=1);

namespace Inverse\Termin\Notifier;

use Inverse\Termin\Config\Config;
use Pushbullet\Pushbullet;
use TelegramBot\Api\BotApi;

class NotifierFactory
{
    private MultiNotifier $notifier;

    public function __construct(MultiNotifier $notifier)
    {
        $this->notifier = $notifier;
    }

    public function create(Config $config): NotifierInterface
    {
        if (null !== $config->getPushbullet()) {
            $pushbullet = new Pushbullet($config->getPushbullet()->getApiToken());
            $pushBulletNotifier = new PushbulletNotifier($pushbullet);
            $this->notifier->addNotifier($pushBulletNotifier);
        }

        if (null !== $config->getTelegram()) {
            $botApi = new BotApi($config->getTelegram()->getApiKey());
            $telegramNotifier = new TelegramNotifier($botApi, $config->getTelegram()->getChatId());
            $this->notifier->addNotifier($telegramNotifier);
        }

        if (null !== $config->getNtfy()) {
            $this->notifier->addNotifier(new NtfyNotifier($config->getNtfy()));
        }

        return $this->notifier;
    }
}
