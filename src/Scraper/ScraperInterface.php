<?php

declare(strict_types=1);

namespace Inverse\Termin\Scraper;

use Inverse\Termin\Config\Site;
use Inverse\Termin\Result;

interface ScraperInterface
{
    /**
     * @return Result[]
     */
    public function scrape(Site $site): array;
}
