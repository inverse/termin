<?php

declare(strict_types=1);

namespace Tests\Inverse\Termin;

use DateTime;
use Inverse\Termin\Config\Rules\RuleInterface;
use Inverse\Termin\Filter;
use Inverse\Termin\Result;
use PHPUnit\Framework\TestCase;

class FilterTest extends TestCase
{
    public function testFilterEmpty(): void
    {
        $filter = new Filter([]);
        self::assertEmpty($filter->applyRules([]));
    }

    public function testFilterNoRules(): void
    {
        $filter = new Filter([]);

        $results = [new Result('', '', new DateTime())];
        self::assertEquals($results, $filter->applyRules($results));
    }

    public function testFilterMatchedRule(): void
    {
        $mockRule = $this->createMock(RuleInterface::class);
        $mockRule->method('passes')->willReturn(true);

        $filter = new Filter([$mockRule]);

        $results = [new Result('', '', new DateTime())];
        self::assertEquals($results, $filter->applyRules($results));
    }

    public function testFilterUnmatchedRule(): void
    {
        $mockRule = $this->createMock(RuleInterface::class);
        $mockRule->method('passes')->willReturn(false);

        $filter = new Filter([$mockRule]);

        $results = [new Result('', '', new DateTime())];
        self::assertEmpty($filter->applyRules($results));
    }
}
