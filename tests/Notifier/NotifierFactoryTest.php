<?php

declare(strict_types=1);

namespace Tests\Inverse\Termin\Notifier;

use Inverse\Termin\Config\Config;
use Inverse\Termin\Config\Notifier\Ntfy;
use Inverse\Termin\Config\Notifier\Pushbullet;
use Inverse\Termin\Config\Notifier\Telegram;
use Inverse\Termin\Notifier\MultiNotifier;
use Inverse\Termin\Notifier\NotifierFactory;
use Inverse\Termin\Notifier\NtfyNotifier;
use Inverse\Termin\Notifier\PushbulletNotifier;
use Inverse\Termin\Notifier\TelegramNotifier;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;

class NotifierFactoryTest extends TestCase
{
    public function testEmpty(): void
    {
        $config = new Config(
            [],
            [],
            Logger::INFO,
            true,
            null,
            null,
            null
        );
        $mockMultiNotifier = $this->createMock(MultiNotifier::class);
        $mockMultiNotifier->expects($this->never())->method('addNotifier');
        $notifierFactory = new NotifierFactory($mockMultiNotifier);

        $notifierFactory->create($config);
    }

    public function testPushbullet(): void
    {
        $config = new Config(
            [],
            [],
            Logger::INFO,
            true,
            new Pushbullet('api'),
            null,
            null
        );

        $mockMultiNotifier = $this->createMock(MultiNotifier::class);
        $mockMultiNotifier
            ->expects($this->once())
            ->method('addNotifier')
            ->with(self::callback(static fn($value): bool => $value instanceof PushbulletNotifier));
        $notifierFactory = new NotifierFactory($mockMultiNotifier);

        $notifierFactory->create($config);
    }

    public function testTelegram(): void
    {
        $config = new Config(
            [],
            [],
            Logger::INFO,
            true,
            null,
            new Telegram('api', '0'),
            null
        );

        $mockMultiNotifier = $this->createMock(MultiNotifier::class);
        $mockMultiNotifier
            ->expects($this->once())
            ->method('addNotifier')
            ->with(self::callback(static fn($value): bool => $value instanceof TelegramNotifier));
        $notifierFactory = new NotifierFactory($mockMultiNotifier);

        $notifierFactory->create($config);
    }

    public function testNtfy(): void
    {
        $config = new Config(
            [],
            [],
            Logger::INFO,
            true,
            null,
            null,
            new Ntfy(Ntfy::DEFAULT_SERVER, 'foobar')
        );

        $mockMultiNotifier = $this->createMock(MultiNotifier::class);
        $mockMultiNotifier
            ->expects($this->once())
            ->method('addNotifier')
            ->with(self::callback(static fn($value): bool => $value instanceof NtfyNotifier));
        $notifierFactory = new NotifierFactory($mockMultiNotifier);

        $notifierFactory->create($config);
    }

    public function testMultiple(): void
    {
        $config = new Config(
            [],
            [],
            Logger::INFO,
            true,
            new Pushbullet('yolo'),
            new Telegram('api', '0'),
            new Ntfy(Ntfy::DEFAULT_SERVER, 'foobar')
        );

        $mockMultiNotifier = $this->createMock(MultiNotifier::class);
        $mockMultiNotifier->expects($this->exactly(3))->method('addNotifier');
        $notifierFactory = new NotifierFactory($mockMultiNotifier);

        $notifierFactory->create($config);
    }
}
