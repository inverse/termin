<?php

declare(strict_types=1);

namespace Inverse\Termin\Config;

class Site
{
    public function __construct(
        private readonly string $label,
        private readonly string $type,
        private readonly array $params
    ) {}

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getParams(): array
    {
        return $this->params;
    }
}
