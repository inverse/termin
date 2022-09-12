<?php

declare(strict_types=1);

namespace Tests\Inverse\Termin\HttpClient;

use Inverse\Termin\HttpClient\HttpClientFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HttpClientFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $factory = new HttpClientFactory();
        self::assertInstanceOf(HttpClientInterface::class, $factory->create());
    }
}
