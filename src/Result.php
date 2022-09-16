<?php

declare(strict_types=1);

namespace Inverse\Termin;

use DateTime;
use DateTimeInterface;

class Result
{
    private string $url;

    private string $label;

    private DateTime $dateTime;

    public function __construct(string $url, string $label, DateTime $dateTime)
    {
        $this->url = $url;
        $this->label = $label;
        $this->dateTime = $dateTime;
    }

    public function __toString()
    {
        return $this->dateTime->format(DateTimeInterface::ATOM);
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getDateTime(): DateTime
    {
        return $this->dateTime;
    }
}
