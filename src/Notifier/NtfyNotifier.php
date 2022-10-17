<?php

declare(strict_types=1);

namespace Inverse\Termin\Notifier;

use DateTime;
use Ntfy\Message;
use Ntfy\Client;

class NtfyNotifier implements NotifierInterface
{
    private Client $client;

    private string $topic;

    public function __construct(Client $client, string $topic)
    {
        $this->client = $client;
        $this->topic = $topic;
    }

    public function notify(string $label, string $url, DateTime $date): void
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
