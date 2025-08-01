<?php

declare(strict_types=1);

namespace Tests\Inverse\Termin\Config;

use Inverse\Termin\Config\LoggerConfig;
use Monolog\Level;
use PHPUnit\Framework\TestCase;

class LoggerConfigTest extends TestCase
{
    public function testParseDefaultValues(): void
    {
        $loggerConfig = new LoggerConfig();
        self::assertEquals(Level::Info, $loggerConfig->getLevel());
        self::assertFalse($loggerConfig->getLogToFile());
        self::assertEquals('./var/log/termin.log', $loggerConfig->getLogFileLocation());
    }
}
