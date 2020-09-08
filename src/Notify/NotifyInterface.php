<?php

declare(strict_types=1);

namespace Inverse\Termin\Notify;

use DateTime;

interface NotifyInterface
{
    public function notify(string $name, string $url, DateTime $date): void;
}
