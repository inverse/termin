<?php

namespace Inverse\Termin\Notify;

use DateTime;

class MultiNotifier implements NotifyInterface
{
    /**
     * @var NotifyInterface[]
     */
    private $notifiers;

    public function __construct()
    {
        $this->notifiers = [];
    }

    public function addNotifier(NotifyInterface $notify): void
    {
        $this->notifiers[] = $notify;
    }

    public function notify(string $name, string $url, DateTime $date): void
    {
        foreach ($this->notifiers as $notify) {
            $notify->notify($name, $url, $date);
        }
    }
}
