<?php

declare(strict_types=1);

namespace Inverse\Termin\HttpClient;

use Campo\UserAgent;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HttpClientFactory implements HttpClientFactoryInterface
{
    public function create(): HttpClientInterface
    {
        return HttpClient::create([
            'headers' => [
                'User-Agent' => $this->getUserAgent(),
            ],
        ]);
    }

    private function getUserAgent(): string
    {
        return UserAgent::random([
            'os_type' => ['Windows', 'OS X', 'Linux'],
            'device_type' => ['Desktop'],
        ]);
    }
}
