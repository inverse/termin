<?php

declare(strict_types=1);

namespace Inverse\Termin\Config;

use Inverse\Termin\Config\Notifier\Ntfy;
use Inverse\Termin\Config\Notifier\Pushbullet;
use Inverse\Termin\Config\Notifier\Telegram;
use Inverse\Termin\Config\Rules\RuleInterface;
use Monolog\Level;

class Config
{
    /**
     * @param Site[]          $sites
     * @param RuleInterface[] $rules
     */
    public function __construct(
        private readonly array $sites,
        private readonly array $rules,
        private readonly Level $logLevel,
        private readonly bool $logToFile,
        private readonly bool $allowMultipleNotifications,
        private readonly ?Pushbullet $pushbullet,
        private readonly ?Telegram $telegram,
        private readonly ?Ntfy $ntfy,
    ) {}

    /**
     * @return Site[]
     */
    public function getSites(): array
    {
        return $this->sites;
    }

    /**
     * @return RuleInterface[]
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    public function getLogLevel(): Level
    {
        return $this->logLevel;
    }

    public function getLogToFile(): bool
    {
        return $this->logToFile;
    }

    public function isAllowMultipleNotifications(): bool
    {
        return $this->allowMultipleNotifications;
    }

    public function getTelegram(): ?Telegram
    {
        return $this->telegram;
    }

    public function getPushbullet(): ?Pushbullet
    {
        return $this->pushbullet;
    }

    public function getNtfy(): ?Ntfy
    {
        return $this->ntfy;
    }
}
