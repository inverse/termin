<?php

namespace Inverse\Termin;

use DateTime;

class Result
{
    /**
     * @var bool
     */
    private $found;

    /**
     * @var null|DateTime
     */
    private $date;

    private function __construct(bool $found, ?DateTime $dateTime = null)
    {
        $this->found = $found;
        $this->date = $dateTime;
    }

    public function isFound(): bool
    {
        return $this->found;
    }

    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    public static function createFound(DateTime $dateTime): self
    {
        return new self(true, $dateTime);
    }

    public static function createNotFound(): self
    {
        return new self(false);
    }
}
