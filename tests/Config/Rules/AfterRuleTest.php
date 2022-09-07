<?php

declare(strict_types=1);

namespace Tests\Inverse\Termin\Config\Rules;

use DateInterval;
use DateTime;
use Inverse\Termin\Config\Rules\AfterRule;
use Inverse\Termin\Result;
use PHPUnit\Framework\TestCase;

class AfterRuleTest extends TestCase
{
    public function testPassesValid(): void
    {
        $rule = new AfterRule('PT24H');
        self::assertTrue($rule->passes(new Result((new DateTime())->add(new DateInterval('PT24H1M')))));
    }

    public function testPassesInvalid(): void
    {
        $rule = new AfterRule('PT24H');
        self::assertFalse($rule->passes(new Result((new DateTime())->add(new DateInterval('PT23H59M')))));
    }
}
