<?php

declare(strict_types=1);

namespace Inverse\Termin;

use InvalidArgumentException;

class SiteParser
{
    public function parse(string $payload): array
    {
        $decoded = json_decode($payload, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new InvalidArgumentException('Invalid JSON given for sites');
        }

        return $decoded;
    }
}
