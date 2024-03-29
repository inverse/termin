<?php

declare(strict_types=1);

namespace Inverse\Termin\Config\Rules;

use Inverse\Termin\Result;

class AfterRule implements RuleInterface
{
    use DateIntervalRuleTrait;

    private \DateInterval $after;

    public function __construct(string $after)
    {
        $this->after = $this->parseDateInterval($after, 'after');
    }

    public function passes(Result $result): bool
    {
        return $result->getDateTime() > (new \DateTime())->add($this->after);
    }
}
