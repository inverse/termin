<?php

declare(strict_types=1);

namespace Inverse\Termin\Config\Rules;

trait DateIntervalRuleTrait
{
    private function parseDateInterval(string $value, string $name): \DateInterval
    {
        try {
            return new \DateInterval($value);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException(sprintf('Failed to parse %s to DateInterval: %s', $name, $e));
        }
    }

    private function parseDateTime(string $value, string $name): \DateTime
    {
        try {
            return new \DateTime($value);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException(sprintf('Failed to parse %s to DateTime: %s', $name, $e));
        }
    }
}
