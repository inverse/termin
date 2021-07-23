<?php

declare(strict_types=1);

namespace Inverse\Termin\Config;

class Site
{
    private string $label;

    private string $url;

    public function __construct(string $label, string $url)
    {
        $this->label = $label;
        $this->url = $url;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
