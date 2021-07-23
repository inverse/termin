<?php

declare(strict_types=1);

namespace Inverse\Termin\Config;

class Telegram
{
    private string $apiKey;

    private int $chatId;

    public function __construct(string $apiKey, int $chatId)
    {
        $this->apiKey = $apiKey;
        $this->chatId = $chatId;
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function getChatId(): int
    {
        return $this->chatId;
    }
}
