<?php

declare(strict_types=1);

namespace Inverse\Termin\Scraper;

use Inverse\Termin\Exceptions\TerminException;

class ScraperLocator
{
    /**
     * @param ScraperInterface[] $scrapers
     */
    public function __construct(private readonly array $scrapers) {}

    /**
     * @throws TerminException
     */
    public function locate(string $type): ScraperInterface
    {
        if (!array_key_exists($type, $this->scrapers)) {
            throw new TerminException(sprintf("Unable to locate scraper for '%s'", $type));
        }

        return $this->scrapers[$type];
    }
}
