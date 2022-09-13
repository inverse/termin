<?php

declare(strict_types=1);

namespace Tests\Inverse\Termin\Notifier;

use DateTime;
use Inverse\Termin\Notifier\TelegramNotifier;
use PHPUnit\Framework\TestCase;
use TelegramBot\Api\BotApi;

class TelegramNotifierTest extends TestCase
{
    public function testNotify(): void
    {
        $mockBotApi = $this->createMock(BotApi::class);
        $mockBotApi
            ->expects($this->once())
            ->method('sendMessage')
            ->with(
                '1234',
                'foo appointment found for [1st Jan 2022](http://example.com/1)',
                'markdown'
            )
        ;
        $chatId = '1234';
        $notifier = new TelegramNotifier($mockBotApi, $chatId);
        $notifier->notify('foo', 'http://example.com/1', new DateTime('2022-01-01 00:00:00'));
    }
}
