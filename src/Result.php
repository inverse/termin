<?php

declare(strict_types=1);

namespace Inverse\Termin;

class Result
{
    public function __construct(
        private readonly string $url,
        private readonly string $label,
        private readonly \DateTime $dateTime
    ) {}

    public function __toString()
    {
        return $this->dateTime->format(\DateTimeInterface::ATOM);
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getDateTime(): \DateTime
    {
        return $this->dateTime;
    }
}
