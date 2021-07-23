<?php

declare(strict_types=1);

namespace Inverse\Termin\Config;

class Config
{
    /**
     * @var Site[]
     */
    private array $sites;

    private bool $allowMultipleNotifications;

    private ?Telegram $telegram;

    private ?Pushbullet $pushbullet;

    /**
     * Config constructor.
     *
     * @param Site[] $sites
     */
    public function __construct(array $sites, bool $allowMultipleNotifications, ?Pushbullet $pushbullet, ?Telegram $telegram)
    {
        $this->sites = $sites;
        $this->allowMultipleNotifications = $allowMultipleNotifications;
        $this->pushbullet = $pushbullet;
        $this->telegram = $telegram;
    }

    /**
     * @return Site[]
     */
    public function getSites(): array
    {
        return $this->sites;
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
}
