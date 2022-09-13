<?php

declare(strict_types=1);

namespace Inverse\Termin\Config\Rules;

use DateTime;
use Inverse\Termin\Result;

class BeforeDateRule implements RuleInterface
{
    use DateIntervalRuleTrait;

    private DateTime $before;

    public function __construct(string $before)
    {
        $this->before = $this->parseDateTime($before, 'before');
    }

    public function passes(Result $result): bool
    {
        return $result->getDateTime() < $this->before;
    }
}
