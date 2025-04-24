<?php

declare(strict_types=1);

namespace Tests\Inverse\Termin;

use Inverse\Termin\Config\Config;
use Inverse\Termin\Container;
use Inverse\Termin\Termin;
use Monolog\Level;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    public function testGetTermin(): void
    {
        $config = new Config([], [], Level::Debug, false, false, null, null, null);
        $container = new Container($config);
        self::assertInstanceOf(Termin::class, $container->getTermin());
    }
}
