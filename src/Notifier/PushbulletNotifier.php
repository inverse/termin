<?php

declare(strict_types=1);

namespace Inverse\Termin\Notifier;

use DateTime;
use Pushbullet\Pushbullet;

class PushbulletNotifier implements NotifierInterface
{
    private Pushbullet $pushbullet;

    public function __construct(Pushbullet $pushbullet)
    {
        $this->pushbullet = $pushbullet;
    }

    public function notify(string $label, string $url, DateTime $date): void
    {
        $title = 'Appointment Found';
        $body = sprintf('%s appointment found for %s', $label, $date->format('jS M Y'));
        $this->pushbullet->allDevices()->pushLink($title, $url, $body);
    }
}
