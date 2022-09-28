<?php

declare(strict_types=1);

namespace Inverse\Termin\Notifier;

use DateTime;
use Inverse\Termin\Config\Notifier\Ntfy;
use Ntfy\Message;
use Ntfy\Server;

class NtfyNotifier implements NotifierInterface
{
    private string $topic;

    private Server $server;

    public function __construct(Ntfy $ntfy)
    {
        $this->topic = $ntfy->getTopic();
        $this->server = new Server($ntfy->getServer());
    }

    public function notify(string $label, string $url, DateTime $date): void
    {
        $body = sprintf('%s appointment found for %s', $label, $date->format('jS M Y'));
        $message = new Message($this->server);
        $message->topic($this->topic);
        $message->title('Appointment Found');
        $message->body($body);
        $message->clickAction($url);
        $message->send();
    }
}
