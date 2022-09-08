<?php

declare(strict_types=1);

namespace Tests\Inverse\Termin\Config\Rules;

use DateInterval;
use DateTime;
use InvalidArgumentException;
use Inverse\Termin\Config\Rules\BetweenRule;
use Inverse\Termin\Result;
use PHPUnit\Framework\TestCase;

class BetweenRuleTest extends TestCase
{
    public function testInvalidInput(): void
    {
        self::expectException(InvalidArgumentException::class);
        new BetweenRule('HelloWorld', 'HelloWorld');
        self::assertStringStartsWith('Failed to parse before', self::getExpectedExceptionMessage());
    }

    public function testPassesValid(): void
    {
        $start = (new DateTime())->sub(new DateInterval('PT12H'));
        $end = (new DateTime())->add(new DateInterval('PT12H'));
        $rule = new BetweenRule($start->format(DateTime::ATOM), $end->format(DateTime::ATOM));
        self::assertTrue($rule->passes(new Result(new DateTime())));
    }

    public function testPassesInvalid(): void
    {
        $start = (new DateTime())->sub(new DateInterval('PT12H'));
        $end = (new DateTime())->add(new DateInterval('PT12H'));
        $rule = new BetweenRule($start->format(DateTime::ATOM), $end->format(DateTime::ATOM));
        self::assertFalse($rule->passes(new Result((new DateTime())->add(new DateInterval('PT13H')))));
    }
}
