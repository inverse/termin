<?php

declare(strict_types=1);

namespace Inverse\Termin;

use Inverse\Termin\Config\Rules\RuleInterface;

class Filter
{
    /**
     * @param RuleInterface[] $rules
     */
    public function __construct(
        private readonly array $rules
    ) {}

    public function applyRules(array $results): array
    {
        foreach ($this->rules as $rule) {
            foreach ($results as $key => $result) {
                if (!$rule->passes($result)) {
                    unset($results[$key]);
                }
            }
        }

        return $results;
    }
}
