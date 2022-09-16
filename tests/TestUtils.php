<?php

declare(strict_types=1);

namespace Tests\Inverse\Termin;

use RuntimeException;

class TestUtils
{
    public static function loadFixture(string $name): string
    {
        $fixturePath = __DIR__.'/Fixtures/'.$name;
        $contents = file_get_contents($fixturePath);

        if (false === $contents) {
            throw new RuntimeException(sprintf('Unable to load fixture: %s', $fixturePath));
        }

        return $contents;
    }
}
