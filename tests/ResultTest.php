<?php

declare(strict_types=1);

namespace Tests\Inverse\Termin;

use Inverse\Termin\Result;
use PHPUnit\Framework\TestCase;

class ResultTest extends TestCase
{
    public function testResult(): void
    {
        $dateTime = new \DateTime('2022-01-01T00:00:00');
        $result = new Result('https://example.com', 'foo_bar', $dateTime);

        self::assertEquals($dateTime, $result->getDateTime());
        self::assertEquals('2022-01-01T00:00:00+00:00', (string) $result);
    }
}
