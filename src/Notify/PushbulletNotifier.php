<?php

namespace Inverse\Termin\Notify;

use DateTime;
use Pushbullet\Pushbullet;

class PushbulletNotifier implements NotifyInterface
{
    /**
     * @var Pushbullet
     */
    private $pushbullet;

    public function __construct(Pushbullet $pushbullet)
    {
        $this->pushbullet = $pushbullet;
    }

    public function notify(string $name, string $url, DateTime $date): void
    {
        $title = 'Appointment Found';
        $body = sprintf('%s appointment found for %s', $name, $date->format('jS M Y'));
        $this->pushbullet->allDevices()->pushLink($title, $url, $body);
    }
}
