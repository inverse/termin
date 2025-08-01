<?php

declare(strict_types=1);

namespace Tests\Inverse\Termin;

use Inverse\Termin\Config\Config;
use Inverse\Termin\Container;
use Inverse\Termin\Termin;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    public function testGetTerminWithDefaultConfig(): void
    {
        $container = new Container(new Config());
        self::assertInstanceOf(Termin::class, $container->getTermin());
    }
}
