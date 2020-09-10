<?php

declare(strict_types=1);

namespace Inverse\Termin\HttpClient;

use Symfony\Contracts\HttpClient\HttpClientInterface;

interface HttpClientFactoryInterface
{
    public function create(): HttpClientInterface;
}
