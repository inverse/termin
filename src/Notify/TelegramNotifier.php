<?php

namespace Inverse\Termin\Notify;

use DateTime;
use TelegramBot\Api\BotApi;

class TelegramNotifier implements NotifyInterface
{
    /**
     * @var BotApi
     */
    private $botApi;

    /**
     * @var int
     */
    private $chatId;

    public function __construct(BotApi $botApi, int $chatId)
    {
        $this->botApi = $botApi;
        $this->chatId = $chatId;
    }

    public function notify(string $name, string $url, DateTime $date): void
    {
        $body = sprintf('%s appointment found for %s', $name, $date->format('jS M Y'));
        $this->botApi->sendMessage($this->chatId, $body);
    }
}
