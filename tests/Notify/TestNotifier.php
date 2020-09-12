<?php

declare(strict_types=1);

namespace Tests\Inverse\Termin\Notify;

use DateTime;
use Inverse\Termin\Notify\NotifyInterface;

class TestNotifier implements NotifyInterface
{
    /**
     * @var array
     */
    private $notifications;

    public function __construct()
    {
        $this->notifications = [];
    }

    public function notify(string $label, string $url, DateTime $date): void
    {
        $this->notifications[] = [
            'name' => $label,
            'url' => $url,
            'date' => $date,
        ];
    }

    public function getNotifications(): array
    {
        return $this->notifications;
    }
}
