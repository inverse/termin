<?php

declare(strict_types=1);

namespace Inverse\Termin\Config\Rules;

use Inverse\Termin\Result;

interface RuleInterface
{
    public function passes(Result $result): bool;
}
