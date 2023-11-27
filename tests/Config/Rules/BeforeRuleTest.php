<?php

declare(strict_types=1);

namespace Tests\Inverse\Termin\Config\Rules;

use Inverse\Termin\Config\Rules\BeforeRule;
use Inverse\Termin\Result;
use PHPUnit\Framework\TestCase;

class BeforeRuleTest extends TestCase
{
    public function testInvalidInput(): void
    {
        self::expectException(\InvalidArgumentException::class);
        new BeforeRule('HelloWorld');
        self::expectExceptionMessage('Failed to parse before');
    }

    public function testPassesValid(): void
    {
        $rule = new BeforeRule('PT24H');
        self::assertTrue($rule->passes(new Result('', '', (new \DateTime())->add(new \DateInterval('PT23H59M')))));
    }

    public function testPassesInvalid(): void
    {
        $rule = new BeforeRule('PT24H');
        self::assertFalse($rule->passes(new Result('', '', (new \DateTime())->add(new \DateInterval('PT24H1M')))));
    }
}
