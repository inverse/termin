<?php

declare(strict_types=1);

namespace Inverse\Termin\Config\Notifier;

class Ntfy
{
    public const DEFAULT_SERVER = 'https://ntfy.sh';

    public function __construct(private readonly string $server, private readonly string $topic) {}

    public function getServer(): string
    {
        return $this->server;
    }

    public function getTopic(): string
    {
        return $this->topic;
    }
}
