<?php

declare(strict_types=1);

namespace Tests\Inverse\Termin\Notifier;

use Inverse\Termin\Notifier\PushbulletNotifier;
use PHPUnit\Framework\TestCase;
use Pushbullet\Device;
use Pushbullet\Pushbullet;

class PushbulletNotifierTest extends TestCase
{
    public function testNotify(): void
    {
        $mockDevice = $this->createMock(Device::class);
        $mockDevice
            ->expects($this->once())
            ->method('pushLink')
            ->with('Appointment Found', 'http://example.com/1', 'foo appointment found for 1st Jan 2022')
        ;

        $mockBotApi = $this->createMock(Pushbullet::class);
        $mockBotApi
            ->expects($this->once())
            ->method('allDevices')
            ->willReturn($mockDevice)
        ;
        $notifier = new PushbulletNotifier($mockBotApi);
        $notifier->notify('foo', 'http://example.com/1', new \DateTime('2022-01-01 00:00:00'));
    }
}
