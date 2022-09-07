<?php

declare(strict_types=1);

namespace Inverse\Termin\Config;

use Inverse\Termin\Config\Rules\RuleInterface;

class Config
{
    /**
     * @var Site[]
     */
    private array $sites;

    /**
     * @var RuleInterface[]
     */
    private array $rules;

    private bool $allowMultipleNotifications;

    private ?Telegram $telegram;

    private ?Pushbullet $pushbullet;

    /**
     * Config constructor.
     *
     * @param Site[]          $sites
     * @param RuleInterface[] $rules
     */
    public function __construct(
        array $sites,
        array $rules,
        bool $allowMultipleNotifications,
        ?Pushbullet $pushbullet,
        ?Telegram $telegram
    ) {
        $this->sites = $sites;
        $this->rules = $rules;
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

    public function getRules(): array
    {
        return $this->rules;
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
