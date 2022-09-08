<?php

declare(strict_types=1);

namespace Tests\Inverse\Termin\Config\Rules;

use DateInterval;
use DateTime;
use InvalidArgumentException;
use Inverse\Termin\Config\Rules\AfterRule;
use Inverse\Termin\Result;
use PHPUnit\Framework\TestCase;

class AfterRuleTest extends TestCase
{
    public function testInvalidInput(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('Failed to parse input: Exception: Unknown or bad format (HelloWorld)'); 
        new AfterRule('HelloWorld');
    }

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
