<?php

declare(strict_types=1);

namespace Inverse\Termin\Config\Rules;

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
        $result->getDate()
    }
}
