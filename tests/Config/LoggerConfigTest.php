<?php

declare(strict_types=1);

namespace Tests\Inverse\Termin\Config;

use Inverse\Termin\Config\LoggerConfig;
use Monolog\Level;
use PHPUnit\Framework\TestCase;

class LoggerConfigTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $loggerConfig = new LoggerConfig();
        self::assertEquals(Level::Info, $loggerConfig->getLevel());
        self::assertFalse($loggerConfig->getLogToFile());
        self::assertEquals('./var/log/termin.log', $loggerConfig->getLogFileLocation());
    }

    public function testOverrideDefaults(): void
    {
        $loggerConfig = new LoggerConfig([
            'level' => 'error',
            'log_to_file' => true,
            'log_file_location' => './var/log/random.log',
        ]);
        self::assertEquals(Level::Error, $loggerConfig->getLevel());
        self::assertTrue($loggerConfig->getLogToFile());
        self::assertEquals('./var/log/random.log', $loggerConfig->getLogFileLocation());
    }
}
