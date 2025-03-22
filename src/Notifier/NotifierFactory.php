<?php

declare(strict_types=1);

namespace Inverse\Termin\Notifier;

use Inverse\Termin\Config\Config;
use Ntfy\Client;
use Ntfy\Server;
use Pushbullet\Pushbullet;
use TelegramBot\Api\BotApi;

class NotifierFactory
{
    public function __construct(
        private readonly MultiNotifier $notifier
    ) {}

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
            $client = new Client(new Server($config->getNtfy()->getServer()));
            $this->notifier->addNotifier(new NtfyNotifier($client, $config->getNtfy()->getTopic()));
        }

        return $this->notifier;
    }
}
