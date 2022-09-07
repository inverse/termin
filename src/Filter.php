<?php

declare(strict_types=1);

namespace Inverse\Termin;

use Inverse\Termin\Config\Rules\RuleInterface;

class Filter
{
    private array $rules;

    /**
     * @param RuleInterface[] $rules
     */
    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

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
