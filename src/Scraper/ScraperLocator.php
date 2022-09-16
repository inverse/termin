<?php

declare(strict_types=1);

namespace Inverse\Termin\Scraper;

use Inverse\Termin\Exceptions\TerminException;

class ScraperLocator
{
    /**
     * @var ScraperInterface[]
     */
    private array $scrapers;

    /**
     * @param ScraperInterface[] $scrapers
     */
    public function __construct(array $scrapers)
    {
        $this->scrapers = $scrapers;
    }

    /**
     * @throws TerminException
     */
    public function locate(string $url): ScraperInterface
    {
        foreach ($this->scrapers as $scraper) {
            foreach ($scraper->supportsDomains() as $domain) {
                if (0 === strpos($url, $domain)) {
                    return $scraper;
                }
            }
        }

        throw new TerminException(sprintf('Unable to locate scraper for %s', $url));
    }
}
