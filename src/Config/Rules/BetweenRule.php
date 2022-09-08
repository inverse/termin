<?php

declare(strict_types=1);

namespace Inverse\Termin\Config\Rules;

use DateTime;
use Inverse\Termin\Result;

class BetweenRule implements RuleInterface
{
    use DateIntervalRuleTrait;

    private DateTime $start;
    private DateTime $end;

    public function __construct(string $start, string $end)
    {
        $this->start = $this->parseDateTime($start, 'start');
        $this->end = $this->parseDateTime($end, 'end');
    }

    public function passes(Result $result): bool
    {
        return
            ($result->getDate() > $this->start)
            && ($result->getDate() < $this->end);
    }
}
