<?php

declare(strict_types=1);

namespace Tests\Inverse\Termin\Notifier;

use DateTime;
use Inverse\Termin\Notifier\MultiNotifier;
use Inverse\Termin\Notifier\NotifierException;
use PHPUnit\Framework\TestCase;

class MultiNotifierTest extends TestCase
{
    public function testNoneConfigured(): void
    {
        $this->expectException(NotifierException::class);
        $multiNotifier = new MultiNotifier();
        $multiNotifier->notify('hello', 'https://example.com', new DateTime());
    }

    public function testNotifySingle(): void
    {
        $testNotifier = new TestNotifier();

        $multiNotifier = new MultiNotifier();
        $multiNotifier->addNotifier($testNotifier);

        $multiNotifier->notify('hello', 'https://example.com', new DateTime());

        $this->assertCount(1, $testNotifier->getNotifications());
    }

    public function testNotifyMultiple(): void
    {
        $testNotifier = new TestNotifier();

        $multiNotifier = new MultiNotifier();
        $multiNotifier->addNotifier($testNotifier);

        $multiNotifier->notify('hello', 'https://example.com', new DateTime());
        $multiNotifier->notify('hello', 'https://example.com', new DateTime());
        $multiNotifier->notify('hello', 'https://example.com', new DateTime());

        $this->assertCount(3, $testNotifier->getNotifications());
    }

    public function testMultiNotifierMultiMessage(): void
    {
        $testNotifier1 = new TestNotifier();
        $testNotifier2 = new TestNotifier();

        $multiNotifier = new MultiNotifier();
        $multiNotifier->addNotifier($testNotifier1);
        $multiNotifier->addNotifier($testNotifier2);

        $multiNotifier->notify('hello', 'https://example.com', new DateTime());
        $multiNotifier->notify('hello', 'https://example.com', new DateTime());
        $multiNotifier->notify('hello', 'https://example.com', new DateTime());

        $this->assertCount(3, $testNotifier1->getNotifications());
        $this->assertCount(3, $testNotifier2->getNotifications());
    }
}
