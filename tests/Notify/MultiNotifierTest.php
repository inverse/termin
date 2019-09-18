<?php

namespace TestsInverse\Termin\Notify;

use DateTime;
use Inverse\Termin\Notify\MultiNotifier;
use PHPUnit\Framework\TestCase;
use Tests\Inverse\Termin\Notify\TestNotifier;

class MultiNotifierTest extends TestCase
{
    public function testNotifySingle(): void
    {
        $testNotifier = new TestNotifier();

        $multiNotifier = new MultiNotifier();
        $multiNotifier->addNotifier($testNotifier);

        $multiNotifier->notify('hello', 'http://example.com', new DateTime());


        $this->assertCount(1, $testNotifier->getNotifications());
    }

    public function testNotifyMultiple(): void
    {
        $testNotifier = new TestNotifier();

        $multiNotifier = new MultiNotifier();
        $multiNotifier->addNotifier($testNotifier);

        $multiNotifier->notify('hello', 'http://example.com', new DateTime());
        $multiNotifier->notify('hello', 'http://example.com', new DateTime());
        $multiNotifier->notify('hello', 'http://example.com', new DateTime());

        $this->assertCount(3, $testNotifier->getNotifications());
    }

    public function testMultiNotifierMultiMessage(): void
    {
        $testNotifier1 = new TestNotifier();
        $testNotifier2 = new TestNotifier();

        $multiNotifier = new MultiNotifier();
        $multiNotifier->addNotifier($testNotifier1);
        $multiNotifier->addNotifier($testNotifier2);

        $multiNotifier->notify('hello', 'http://example.com', new DateTime());
        $multiNotifier->notify('hello', 'http://example.com', new DateTime());
        $multiNotifier->notify('hello', 'http://example.com', new DateTime());

        $this->assertCount(3, $testNotifier1->getNotifications());
        $this->assertCount(3, $testNotifier2->getNotifications());
    }


}
