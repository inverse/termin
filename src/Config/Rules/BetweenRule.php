<?php

declare(strict_types=1);

namespace Inverse\Termin\Config\Rules;

use DateInterval;
use DateTime;
use Exception;
use InvalidArgumentException;
use Inverse\Termin\Result;

class BetweenRule implements RuleInterface
{
    use DateIntervalRuleTrait;

    private DateInterval $before;
    private DateInterval $after;

    public function __construct(string $before, string $after)
    {
        $this->before = $this->parseDateInterval($before, 'before');
        $this->after = $this->parseDateInterval($after, 'after');
    }

    public function passes(Result $result): bool
    {
        $now = new DateTime();

        return $result->getDate() > $now->add($this->dateInterval);
    }
}
