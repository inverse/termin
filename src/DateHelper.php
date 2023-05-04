<?php

declare(strict_types=1);

namespace Inverse\Termin;

class DateHelper
{
    public static function createDateTime(string $dateStr): \DateTime
    {   
        $dateTime = \DateTime::createFromFormat('d.m.Y', $dateStr, new \DateTimeZone('Europe/Berlin'));
        $dateTime->setTime(0, 0 ,0, 0);
        return $dateTime;
    }
}
