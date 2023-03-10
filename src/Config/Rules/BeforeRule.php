<?php

declare(strict_types=1);

namespace Inverse\Termin\Config\Rules;

use Inverse\Termin\Result;

class BeforeRule implements RuleInterface
{
    use DateIntervalRuleTrait;

    private \DateInterval $before;

    public function __construct(string $before)
    {
        $this->before = $this->parseDateInterval($before, 'before');
    }

    public function passes(Result $result): bool
    {
        return $result->getDateTime() < (new \DateTime())->add($this->before);
    }
}
