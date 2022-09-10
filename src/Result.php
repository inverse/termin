<?php

declare(strict_types=1);

namespace Inverse\Termin;

use DateTime;
use DateTimeInterface;

class Result
{
    private DateTime $dateTime;

    public function __construct(DateTime $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    public function __toString()
    {
        return $this->dateTime->format(DateTimeInterface::ATOM);
    }

    public function getDateTime(): DateTime
    {
        return $this->dateTime;
    }
}
