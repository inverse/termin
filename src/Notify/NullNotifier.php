<?php

declare(strict_types=1);

namespace Inverse\Termin\Notify;

use DateTime;

class NullNotifier implements NotifyInterface
{
    public function notify(string $label, string $url, DateTime $date): void
    {
        // Do nothing
    }
}
