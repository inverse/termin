<?php

declare(strict_types=1);

namespace Inverse\Termin;

use InvalidArgumentException;

class SiteParser
{
    public function parse(string $payload): array
    {
        $decoded = json_decode($payload, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException('Invalid JSON given for sites');
        }

        return $decoded;
    }
}
