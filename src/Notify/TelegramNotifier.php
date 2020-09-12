<?php

declare(strict_types=1);

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

    public function notify(string $label, string $url, DateTime $date): void
    {
        $body = sprintf('%s appointment found for [%s](%s)', $label, $date->format('jS M Y'), $url);
        $this->botApi->sendMessage($this->chatId, $body, 'markdown');
    }
}
