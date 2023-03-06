<?php

declare(strict_types=1);

namespace Tests\Inverse\Termin\Notifier;

use Inverse\Termin\Notifier\NtfyNotifier;
use Ntfy\Client;
use Ntfy\Message;
use PHPUnit\Framework\TestCase;

class NtfyNotifierTest extends TestCase
{
    public function testNotify(): void
    {
        $topic = 'foobar';
        $message = new Message();
        $message->topic($topic);
        $message->body('foo appointment found for 1st Jan 2022');
        $message->clickAction('http://example.com/1');
        $message->title('Appointment Found');

        $mockClient = $this->createMock(Client::class);
        $mockClient
            ->expects($this->once())
            ->method('send')
            ->with($message)
        ;

        $notifier = new NtfyNotifier($mockClient, $topic);
        $notifier->notify('foo', 'http://example.com/1', new \DateTime('2022-01-01 00:00:00'));
    }
}
