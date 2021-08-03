<?php

declare(strict_types=1);

namespace Inverse\Termin\Notifier;

use DateTime;

interface NotifyInterface
{
    public function notify(string $label, string $url, DateTime $date): void;
}
