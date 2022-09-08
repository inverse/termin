<?php

declare(strict_types=1);

namespace Inverse\Termin\Config\Rules;

use DateInterval;
use DateTime;
use Exception;
use InvalidArgumentException;
use Inverse\Termin\Result;

trait DateIntervalRuleTrait
{
    private function parseDateInterval(string $value, string $name): DateInterval
    {
        try {
            return new DateInterval($value);
        } catch (Exception $e) {
            throw new InvalidArgumentException(sprintf('Failed to parse %s: %s', $name, $e));
        }
    }
}
