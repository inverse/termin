<?php

declare(strict_types=1);

namespace Inverse\Termin\Notifier;

use Ntfy\Client;
use Ntfy\Message;

class NtfyNotifier implements NotifierInterface
{
    public function __construct(
        private readonly Client $client,
        private readonly string $topic
    ) {}

    public function notify(string $label, string $url, \DateTime $date): void
    {
        $body = sprintf('%s appointment found for %s', $label, $date->format('jS M Y'));
        $message = new Message();
        $message->topic($this->topic);
        $message->title('Appointment Found');
        $message->body($body);
        $message->clickAction($url);
        $this->client->send($message);
    }
}
