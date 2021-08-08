<?php

declare(strict_types=1);

namespace Tests\Inverse\Termin;

use Inverse\Termin\DateHelper;
use PHPUnit\Framework\TestCase;

class DateHelperTest extends TestCase
{
    public function testCreateDateTimeEmpty(): void
    {
        self::assertNull(DateHelper::createDateTime('', ''));
        self::assertNull(DateHelper::createDateTime('', 'January'));
        self::assertNull(DateHelper::createDateTime('1', ''));
    }

    public function testCreateDateTimeValid(): void
    {
        $datetime = DateHelper::createDateTime('01', 'January');
        self::assertEquals('01-01', $datetime->format('m-d'));
    }

    public function testCreateDateTimeInvalid(): void
    {
        self::assertNull(DateHelper::createDateTime('01', 'Maruary'));
    }

    public function testMonthConvertValid(): void
    {
        self::assertEquals('January', DateHelper::monthConvert('Januar'));
        self::assertEquals('December', DateHelper::monthConvert('Dezember'));
    }

    public function testMonthConvertInvalid(): void
    {
        self::assertEquals('Random', DateHelper::monthConvert('Random'));
    }
}
