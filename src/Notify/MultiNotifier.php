<?php

declare(strict_types=1);

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

    public function notify(string $label, string $url, DateTime $date): void
    {
        if (empty($this->notifiers)) {
            throw new NotifierException('No notifiers configured');
        }

        foreach ($this->notifiers as $notify) {
            $notify->notify($label, $url, $date);
        }
    }
}
