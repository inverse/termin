<?php

declare(strict_types=1);

namespace Inverse\Termin\Config\Rules;

use DateInterval;
use DateTime;
use Inverse\Termin\Result;

class BeforeRule implements RuleInterface
{
    use DateIntervalRuleTrait;

    private DateInterval $before;

    public function __construct(string $before)
    {
        $this->before = $this->parseDateInterval($before, 'before');
    }

    public function passes(Result $result): bool
    {
        return $result->getDate() < (new DateTime())->add($this->before);
    }
}
