<?php

declare(strict_types=1);

namespace Tests\Inverse\Termin\Config\Rules;

use Inverse\Termin\Config\Rules\BeforeDateRule;
use Inverse\Termin\Result;
use PHPUnit\Framework\TestCase;

class BeforeDateRuleTest extends TestCase
{
    public function testInvalidInput(): void
    {
        self::expectException(\InvalidArgumentException::class);
        new BeforeDateRule('HelloWorld');
        self::assertStringStartsWith('Failed to parse before', self::getExpectedExceptionMessage());
    }

    public function testPassesValid(): void
    {
        $rule = new BeforeDateRule('2021-01-01 00:00:01');
        self::assertTrue($rule->passes(new Result('', '', new \DateTime('2021-01-01 00:00:00'))));
    }

    public function testPassesInvalid(): void
    {
        $rule = new BeforeDateRule('2021-01-01 00:00:00');
        self::assertFalse($rule->passes(new Result('', '', new \DateTime('2021-01-01 00:00:01'))));
    }
}
