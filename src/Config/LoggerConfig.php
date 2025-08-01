<?php

declare(strict_types=1);

namespace Inverse\Termin\Config;

use Monolog\Level;
use Monolog\Logger;

class LoggerConfig
{
    private const DEFAULT_LOG_LEVEL = 'info';
    private const DEFAULT_LOG_FILE_LOCATION = './var/log/termin.log';

    private Level $level;

    private bool $logToFile;

    private string $logFileLocation;

    public function __construct(array $config = [])
    {
        $loggerConfig = $config['logger'] ?? [];
        $this->level = Logger::toMonologLevel($loggerConfig['level'] ?? self::DEFAULT_LOG_LEVEL);
        $this->logToFile = $loggerConfig['file'] ?? false;
        $this->logFileLocation = $loggerConfig['log_file_location'] ?? self::DEFAULT_LOG_FILE_LOCATION;
    }

    public function getLogFileLocation(): string
    {
        return $this->logFileLocation;
    }

    public function getLogToFile(): bool
    {
        return $this->logToFile;
    }

    public function getLevel(): Level
    {
        return $this->level;
    }
}
