<?php

declare(strict_types=1);

namespace Inverse\Termin\Notifier;

use DateTime;

class MultiNotifier implements NotifierInterface
{
    /**
     * @var NotifierInterface[]
     */
    private array $notifiers;

    public function __construct()
    {
        $this->notifiers = [];
    }

    public function addNotifier(NotifierInterface $notify): void
    {
        $this->notifiers[] = $notify;
    }

    public function notify(string $label, string $url, DateTime $date): void
    {
        if (empty($this->notifiers)) {
            throw new NotifierException('No notifiers configured');
        }

        foreach ($this->notifiers as $notify) {
            $notify->notify($label, $url, $date);
        }
    }

    public function registeredNotifierCount(): int
    {
        return count($this->notifiers);
    }
}
