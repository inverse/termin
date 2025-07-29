<?php

declare(strict_types=1);

namespace Tests\Inverse\Termin\Notifier;

use Inverse\Termin\Exceptions\MultiNotifierException;
use Inverse\Termin\Exceptions\NotifierException;
use Inverse\Termin\Notifier\MultiNotifier;
use PHPUnit\Framework\TestCase;

class MultiNotifierTest extends TestCase
{
    public function testNoneConfigured(): void
    {
        $this->expectException(NotifierException::class);
        $multiNotifier = new MultiNotifier();
        $multiNotifier->notify('hello', 'https://example.com', new \DateTime());
    }

    public function testNotifySingle(): void
    {
        $testNotifier = new TestNotifier();

        $multiNotifier = new MultiNotifier();
        $multiNotifier->addNotifier($testNotifier);

        $multiNotifier->notify('hello', 'https://example.com', new \DateTime());

        self::assertCount(1, $testNotifier->getNotifications());
    }

    public function testNotifyMultiple(): void
    {
        $testNotifier = new TestNotifier();

        $multiNotifier = new MultiNotifier();
        $multiNotifier->addNotifier($testNotifier);

        $multiNotifier->notify('hello', 'https://example.com', new \DateTime());
        $multiNotifier->notify('hello', 'https://example.com', new \DateTime());
        $multiNotifier->notify('hello', 'https://example.com', new \DateTime());

        self::assertCount(3, $testNotifier->getNotifications());
    }

    public function testMultiNotifierMultiMessage(): void
    {
        $testNotifier1 = new TestNotifier();
        $testNotifier2 = new TestNotifier();

        $multiNotifier = new MultiNotifier();
        $multiNotifier->addNotifier($testNotifier1);
        $multiNotifier->addNotifier($testNotifier2);

        $multiNotifier->notify('hello', 'https://example.com', new \DateTime());
        $multiNotifier->notify('hello', 'https://example.com', new \DateTime());
        $multiNotifier->notify('hello', 'https://example.com', new \DateTime());

        self::assertCount(3, $testNotifier1->getNotifications());
        self::assertCount(3, $testNotifier2->getNotifications());
    }

    public function testMultiNotifierException(): void
    {
        $message = 'Something went wrong in $testNotifier1';
        $testNotifier1 = new TestNotifier(new NotifierException($message));
        $testNotifier2 = new TestNotifier();

        $multiNotifier = new MultiNotifier();
        $multiNotifier->addNotifier($testNotifier1);
        $multiNotifier->addNotifier($testNotifier2);

        try {
            $multiNotifier->notify('hello', 'https://example.com', new \DateTime());
        } catch (MultiNotifierException $exception) {
            self::assertCount(1, $exception->getExceptions());
            $wrappedException = $exception->getExceptions()[0];
            self::assertEquals($message, $wrappedException->getMessage());
            self::assertInstanceOf(NotifierException::class, $wrappedException);
        }

        self::assertCount(0, $testNotifier1->getNotifications());
        self::assertCount(1, $testNotifier2->getNotifications());
    }
}
