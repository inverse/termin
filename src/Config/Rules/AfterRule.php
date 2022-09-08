<?php

declare(strict_types=1);

namespace Inverse\Termin\Config\Rules;

use DateInterval;
use DateTime;
use Exception;
use InvalidArgumentException;
use Inverse\Termin\Result;

class AfterRule implements RuleInterface
{
    private DateInterval $dateInterval;

    public function __construct(string $value)
    {
        try {
            $this->dateInterval = new DateInterval($value);
        } catch (Exception $e) {
            throw new InvalidArgumentException(sprintf('Failed to parse input: %s', $e));
        }
    }

    public function passes(Result $result): bool
    {
        return $result->getDate() > (new DateTime())->add($this->dateInterval);
    }
}
