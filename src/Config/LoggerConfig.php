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
        $this->level = Logger::toMonologLevel($config['level'] ?? self::DEFAULT_LOG_LEVEL);
        $this->logToFile = $config['log_to_file'] ?? false;
        $this->logFileLocation = $config['log_file_location'] ?? self::DEFAULT_LOG_FILE_LOCATION;
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
