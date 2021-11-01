<?php

declare(strict_types=1);

namespace Tests\Inverse\Termin\Notifier;

use Inverse\Termin\Config\Config;
use Inverse\Termin\Config\Pushbullet;
use Inverse\Termin\Config\Telegram;
use Inverse\Termin\Notifier\MultiNotifier;
use Inverse\Termin\Notifier\NotifierFactory;
use PHPUnit\Framework\TestCase;

class NotifierFactoryTest extends TestCase
{
    public function testEmpty(): void
    {
        $config = new Config([], true, null, null);
        $mockMultiNotifier = self::createMock(MultiNotifier::class);
        $mockMultiNotifier->expects($this->never())->method('addNotifier');
        $notifierFactory = new NotifierFactory($mockMultiNotifier);

        $notifierFactory->create($config);
    }

    public function testPushbullet(): void
    {
        $config = new Config([], true, new Pushbullet('api'), null);

        $mockMultiNotifier = self::createMock(MultiNotifier::class);
        $mockMultiNotifier->expects($this->once())->method('addNotifier');
        $notifierFactory = new NotifierFactory($mockMultiNotifier);

        $notifierFactory->create($config);
    }

    public function testTelegram(): void
    {
        $config = new Config([], true, null, new Telegram('api', '0'));

        $mockMultiNotifier = self::createMock(MultiNotifier::class);
        $mockMultiNotifier->expects($this->once())->method('addNotifier');
        $notifierFactory = new NotifierFactory($mockMultiNotifier);

        $notifierFactory->create($config);
    }

    public function testBoth(): void
    {
        $config = new Config([], true, new Pushbullet('yolo'), new Telegram('api', '0'));

        $mockMultiNotifier = self::createMock(MultiNotifier::class);
        $mockMultiNotifier->expects($this->exactly(2))->method('addNotifier');
        $notifierFactory = new NotifierFactory($mockMultiNotifier);

        $notifierFactory->create($config);
    }
}
