<?php

declare(strict_types=1);

namespace Inverse\Termin\Config;

class Telegram
{
    private string $apiKey;

    private string $chatId;

    public function __construct(string $apiKey, string $chatId)
    {
        $this->apiKey = $apiKey;
        $this->chatId = $chatId;
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function getChatId(): string
    {
        return $this->chatId;
    }
}
