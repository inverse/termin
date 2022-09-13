<?php

declare(strict_types=1);

namespace Inverse\Termin\Config\Rules;

use DateTime;
use Inverse\Termin\Result;

class AfterDateRule implements RuleInterface
{
    use DateIntervalRuleTrait;

    private DateTime $after;

    public function __construct(string $after)
    {
        $this->after = $this->parseDateTime($after, 'after');
    }

    public function passes(Result $result): bool
    {
        return $result->getDateTime() > $this->after;
    }
}
