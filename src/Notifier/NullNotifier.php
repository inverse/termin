<?php

declare(strict_types=1);

namespace Inverse\Termin\Notifier;

class NullNotifier implements NotifierInterface
{
    public function notify(string $label, string $url, \DateTime $date): void
    {
        // Do nothing
    }
}
