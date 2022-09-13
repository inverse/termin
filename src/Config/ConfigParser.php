<?php

declare(strict_types=1);

namespace Inverse\Termin\Config;

use InvalidArgumentException;
use Inverse\Termin\Config\Notifier\Pushbullet;
use Inverse\Termin\Config\Notifier\Telegram;
use Inverse\Termin\Config\Rules\AfterDateRule;
use Inverse\Termin\Config\Rules\AfterRule;
use Inverse\Termin\Config\Rules\BeforeDateRule;
use Inverse\Termin\Config\Rules\BeforeRule;
use Monolog\Logger;

class ConfigParser
{
    private const DEFAULT_LOG_LEVEL = 'info';

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

        $rules = $this->getRules($config);

        $logLevel = $this->getLogLevel($config);

        return new Config($sites, $rules, $logLevel, $allowMultipleNotifications, $pushbullet, $telegram);
    }

    private function getLogLevel(array $config): int
    {
        $logger = $config['logger'] ?? [];

        $level = $logger['level'] ?? self::DEFAULT_LOG_LEVEL;

        return Logger::toMonologLevel($level);
    }

    private function getRules(array $config): array
    {
        $rules = [];

        if (!array_key_exists('rules', $config)) {
            return $rules;
        }

        if (!is_array($config['rules'])) {
            throw new InvalidArgumentException('rules must be an array');
        }

        foreach ($config['rules'] as $rule) {
            if (!is_array($rule)) {
                throw new InvalidArgumentException('rule must be an array');
            }

            $type = $rule['type'];

            switch ($type) {
                case 'before':
                    $rules[] = new BeforeRule($rule['param']);

                    break;

                case 'after':
                    $rules[] = new AfterRule($rule['param']);

                    break;

                case 'before_date':
                    $rules[] = new BeforeDateRule($rule['param']);

                    break;

                case 'after_date':
                    $rules[] = new AfterDateRule($rule['param']);

                    break;

                default:
                    throw new InvalidArgumentException(sprintf('%s is an invalid rule type', $type));
            }
        }

        return $rules;
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
