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
    use DateIntervalRuleTrait;

    private DateInterval $dateInterval;

    public function __construct(string $after)
    {
       $this->dateInterval = $this->parseDateInterval($after, 'after');
    }

    public function passes(Result $result): bool
    {
        return $result->getDate() > (new DateTime())->add($this->dateInterval);
    }
}