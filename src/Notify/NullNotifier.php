<?php

namespace Inverse\Termin\Notify;

use DateTime;

class NullNotifier implements NotifyInterface
{
    public function notify(string $name, string $url, DateTime $date): void
    {
        // Do nothing
    }
}