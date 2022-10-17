<?php

declare(strict_types=1);

namespace Inverse\Termin\Config\Notifier;

class Ntfy
{
    public const DEFAULT_SERVER = 'https://ntfy.sh';

    private string $server;

    private string $topic;

    public function __construct(string $server, string $topic)
    {
        $this->server = $server;
        $this->topic = $topic;
    }

    public function getServer(): string
    {
        return $this->server;
    }

    public function getTopic(): string
    {
        return $this->topic;
    }
}
