<?php

declare(strict_types=1);

namespace Inverse\Termin\Scraper;

use Inverse\Termin\Result;

interface ScraperInterface
{
    /**
     * @return Result[]
     */
    public function scrapeSite(string $url): array;

    /**
     * @return string[]
     */
    public function supportsDomains(): array;
}
