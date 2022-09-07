<?php

declare(strict_types=1);

namespace Inverse\Termin\Config\Rules;

use DateInterval;
use DateTime;
use Inverse\Termin\Result;

class AfterRule implements RuleInterface
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function passes(Result $result): bool
    {
        return $result->getDate() > (new DateTime())->add(new DateInterval($this->value));
    }
}
