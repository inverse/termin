<?php

declare(strict_types=1);

namespace Tests\Inverse\Termin\Config\Rules;

use DateInterval;
use DateTime;
use InvalidArgumentException;
use Inverse\Termin\Config\Rules\BeforeRule;
use Inverse\Termin\Result;
use PHPUnit\Framework\TestCase;

class BeforeRuleTest extends TestCase
{
    public function testInvalidInput(): void
    {
        self::expectException(InvalidArgumentException::class);
        new BeforeRule('HelloWorld');
        self::assertStringStartsWith('Failed to parse before', self::getExpectedExceptionMessage());
    }

    public function testPassesValid(): void
    {
        $rule = new BeforeRule('PT24H');
        self::assertTrue($rule->passes(new Result('', '', (new DateTime())->add(new DateInterval('PT23H59M')))));
    }

    public function testPassesInvalid(): void
    {
        $rule = new BeforeRule('PT24H');
        self::assertFalse($rule->passes(new Result('', '', (new DateTime())->add(new DateInterval('PT24H1M')))));
    }
}
