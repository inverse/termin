<?php

namespace Tests\Inverse\Termin\Notify;

use DateTime;
use Inverse\Termin\Notify\NotifyInterface;

class TestNotifier implements NotifyInterface
{
    /**
     * @var array
     */
    private $notifications;

    public function notify(string $name, string $url, DateTime $date): void
    {
        $this->notifications[] = [
            'name' => $name,
            'url'  => $url,
            'date' => $date,
        ];
    }

    public function getNotifications(): array
    {
        return $this->notifications;
    }
}
