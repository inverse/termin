<?php

declare(strict_types=1);

namespace Inverse\Termin\Notifier;

use Pushbullet\Pushbullet;

class PushbulletNotifier implements NotifierInterface
{
    public function __construct(
        private readonly Pushbullet $pushbullet
    ) {}

    public function notify(string $label, string $url, \DateTime $date): void
    {
        $title = 'Appointment Found';
        $body = sprintf('%s appointment found for %s', $label, $date->format('jS M Y'));
        $this->pushbullet->allDevices()->pushLink($title, $url, $body);
    }
}
