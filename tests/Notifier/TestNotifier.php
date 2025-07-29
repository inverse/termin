<?php

declare(strict_types=1);

namespace Tests\Inverse\Termin\Notifier;

use Inverse\Termin\Notifier\NotifierInterface;

class TestNotifier implements NotifierInterface
{
    private array $notifications;

    private ?\Exception $exception;

    public function __construct(?\Exception $exception = null)
    {
        $this->notifications = [];
        $this->exception = $exception;
    }

    public function notify(string $label, string $url, \DateTime $date): void
    {
        if (null !== $this->exception) {
            throw $this->exception;
        }

        $this->notifications[] = [
            'name' => $label,
            'url' => $url,
            'date' => $date,
        ];
    }

    public function getNotifications(): array
    {
        return $this->notifications;
    }
}
