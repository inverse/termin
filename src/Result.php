<?php

declare(strict_types=1);

namespace Inverse\Termin;

use DateTime;

class Result
{
    /**
     * @var DateTime
     */
    private $date;

    private function __construct(?DateTime $dateTime)
    {
        $this->date = $dateTime;
    }

    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    public static function createFound(DateTime $dateTime): self
    {
        return new self($dateTime);
    }
}
