<?php

declare(strict_types=1);

namespace Inverse\Termin\Config;

use InvalidArgumentException;

class ConfigParser
{
    public function parse(array $config): Config
    {
        if (!array_key_exists('sites', $config)) {
            throw new InvalidArgumentException('config missing sites key');
        }

        if (0 === count($config['sites'])) {
            throw new InvalidArgumentException('config has no sites defined');
        }

        $sites = [];
        foreach ($config['sites'] as $site) {
            if (!array_key_exists('label', $site)) {
                throw new InvalidArgumentException('site missing label field');
            }

            if (!array_key_exists('url', $site)) {
                throw new InvalidArgumentException('site missing url field');
            }

            $sites[] = new Site($site['label'], $site['url']);
        }

        $allowMultipleNotifications = $config['allow_multiple_notifications'] ?? false;

        $telegram = $this->getTelegram($config);

        $pushbullet = $this->getPushBullet($config);

        return new Config($sites, $allowMultipleNotifications, $pushbullet, $telegram);
    }

    private function getTelegram(array $config): ?Telegram
    {
        if (!array_key_exists('telegram', $config)) {
            return null;
        }

        $telegramConfig = $config['telegram'];
        if (!array_key_exists('api_key', $telegramConfig)) {
            throw new InvalidArgumentException('config.telegram missing api_key field');
        }

        if (!array_key_exists('chat_id', $telegramConfig)) {
            throw new InvalidArgumentException('config.telegram missing chat_id field');
        }

        if (empty($telegramConfig['api_key'] || empty($telegramConfig['chat_id']))) {
            return null;
        }

        return new Telegram($telegramConfig['api_key'], $telegramConfig['chat_id']);
    }

    private function getPushBullet(array $config): ?Pushbullet
    {
        if (!array_key_exists('pushbullet', $config)) {
            return null;
        }
        $pushbulletConfig = $config['pushbullet'];
        if (!array_key_exists('api_token', $pushbulletConfig)) {
            throw new InvalidArgumentException('config.pushbullet missing api_token field');
        }

        if (empty($pushbulletConfig['api_token'])) {
            return null;
        }

        return new Pushbullet($pushbulletConfig['api_token']);
    }
}
