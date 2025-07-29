<?php

declare(strict_types=1);

namespace Inverse\Termin\Notifier;

use Inverse\Termin\Exceptions\MultiNotifierException;
use Inverse\Termin\Exceptions\NotifierException;

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

    public function notify(string $label, string $url, \DateTime $date): void
    {
        if (empty($this->notifiers)) {
            throw new NotifierException('No notifiers configured');
        }

        $exceptions = [];

        foreach ($this->notifiers as $notify) {
            try {
                $notify->notify($label, $url, $date);
            } catch (\Throwable $exception) {
                $exceptions[] = $exception;
            }
        }

        if (!empty($exceptions)) {
            throw new MultiNotifierException('One of more exception was raised when notifying', $exceptions);
        }
    }

    public function registeredNotifierCount(): int
    {
        return count($this->notifiers);
    }
}
