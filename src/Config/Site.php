<?php

declare(strict_types=1);

namespace Inverse\Termin\Config;

class Site
{
    private string $label;

    private string $type;

    private array $params;

    public function __construct(string $label, string $type, array $params)
    {
        $this->label = $label;
        $this->type = $type;
        $this->params = $params;
    }

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
