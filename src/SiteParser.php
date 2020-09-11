<?php

declare(strict_types=1);

namespace Inverse\Termin;

use InvalidArgumentException;

class SiteParser
{
    /**
     * @return Site[]
     */
    public function parse(string $payload): array
    {
        $decoded = json_decode($payload, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new InvalidArgumentException('Invalid JSON given for sites');
        }

        $results = [];

        foreach ($decoded as $item) {
            if (!array_key_exists('label', $item)) {
                throw new InvalidArgumentException('site missing label field');
            }

            if (!array_key_exists('url', $item)) {
                throw new InvalidArgumentException('site missing url field');
            }

            $results[] = new Site($item['label'], $item['url']);
        }

        return $results;
    }
}
