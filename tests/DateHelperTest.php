<?php

declare(strict_types=1);

namespace Tests\Inverse\Termin;

use Inverse\Termin\DateHelper;
use PHPUnit\Framework\TestCase;

class DateHelperTest extends TestCase
{
    public function testCreateDateTimeValid(): void
    {
        $datetime = DateHelper::createDateTime('01.01.2023');
        self::assertEquals('01-01-2023', $datetime->format('m-d-Y'));
    }

    public function testCreateDateTimeInvalid(): void
    {
        self::expectException(\InvalidArgumentException::class);
        DateHelper::createDateTime('foo');
    }
}
