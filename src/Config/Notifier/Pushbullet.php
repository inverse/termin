<?php

declare(strict_types=1);

namespace Inverse\Termin\Config\Notifier;

class Pushbullet
{
    private string $apiToken;

    public function __construct(string $apiToken)
    {
        $this->apiToken = $apiToken;
    }

    public function getApiToken(): string
    {
        return $this->apiToken;
    }
}
