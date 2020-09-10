<?php

declare(strict_types=1);

namespace Inverse\Termin\HttpClient;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HttpClientFactory implements HttpClientFactoryInterface
{
    /**
     * Needed to by pass restrictions with setting class attributes for availability.
     */
    private const USER_AGENT = 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.101 Safari/537.36';

    public function create(): HttpClientInterface
    {
        return HttpClient::create([
            'headers' => [
                'User-Agent' => self::USER_AGENT,
            ],
        ]);
    }
}
