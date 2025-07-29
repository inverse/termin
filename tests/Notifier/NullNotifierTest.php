<?php

declare(strict_types=1);

namespace Tests\Inverse\Termin\Notifier;

use Inverse\Termin\Notifier\NotifierInterface;
use Inverse\Termin\Notifier\NullNotifier;
use PHPUnit\Framework\TestCase;

class NullNotifierTest extends TestCase
{
    public function testNotifier(): void
    {
        $notifier = new NullNotifier();
        $notifier->notify('foo', 'http://example.com/1', new \DateTime('2022-01-01 00:00:00'));
        self::assertInstanceOf(NotifierInterface::class, $notifier);
    }
}
