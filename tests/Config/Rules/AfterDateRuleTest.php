<?php

declare(strict_types=1);

namespace Tests\Inverse\Termin\Config\Rules;

use DateTime;
use InvalidArgumentException;
use Inverse\Termin\Config\Rules\AfterDateRule;
use Inverse\Termin\Result;
use PHPUnit\Framework\TestCase;

class AfterDateRuleTest extends TestCase
{
    public function testInvalidInput(): void
    {
        self::expectException(InvalidArgumentException::class);
        new AfterDateRule('HelloWorld');
        self::assertStringStartsWith('Failed to parse before', self::getExpectedExceptionMessage());
    }

    public function testPassesValid(): void
    {
        $rule = new AfterDateRule('2021-01-01 00:00:00');
        self::assertTrue($rule->passes(new Result(new DateTime('2021-01-01 00:00:01'))));
    }

    public function testPassesInvalid(): void
    {
        $rule = new AfterDateRule('2021-01-01 00:00:01');
        self::assertFalse($rule->passes(new Result(new DateTime('2021-01-01 00:00:00'))));
    }
}
