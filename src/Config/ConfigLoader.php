<?php

declare(strict_types=1);

namespace Inverse\Termin\Config;

use Inverse\Termin\Exceptions\NoConfigFoundException;
use Symfony\Component\Yaml\Yaml;

class ConfigLoader
{
    private string $configDirectory;

    public function __construct(string $configDirectory)
    {
        $this->configDirectory = $configDirectory;
    }

    public function load(): array
    {
        if (file_exists($this->yamlFile())) {
            return Yaml::parseFile($this->yamlFile());
        }

        throw new NoConfigFoundException('Failed to load configuration.');
    }

    private function yamlFile(): string
    {
        return $this->configDirectory.'/config.yml';
    }
}
