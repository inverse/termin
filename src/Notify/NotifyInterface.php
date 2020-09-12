<?php

declare(strict_types=1);

namespace Inverse\Termin\Notify;

use DateTime;

interface NotifyInterface
{
    public function notify(string $label, string $url, DateTime $date): void;
}
