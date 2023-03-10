<?php

declare(strict_types=1);

namespace Inverse\Termin;

class DateHelper
{
    public static function createDateTime(string $day, string $month): ?\DateTime
    {
        if (empty($day) || empty($month)) {
            return null;
        }

        try {
            return new \DateTime(sprintf('%s %s', $day, $month), new \DateTimeZone('Europe/Berlin'));
        } catch (\Throwable $exception) {
            return null;
        }
    }

    public static function monthConvert(string $monthStr): string
    {
        $mapper = ['Januar' => 'January',
            'Februar' => 'February',
            'März' => 'March',
            'April' => 'April',
            'Mai' => 'May',
            'Juni' => 'June',
            'Juli' => 'July',
            'August' => 'August',
            'September' => 'September',
            'Oktober' => 'October',
            'November' => 'November',
            'Dezember' => 'December',
        ];

        foreach ($mapper as $month => $replace) {
            if (false !== strpos($monthStr, $month)) {
                return str_replace($month, $replace, $monthStr);
            }
        }

        return $monthStr;
    }
}
