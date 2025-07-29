<?php

declare(strict_types=1);

namespace Inverse\Termin\Exceptions;

class MultiNotifierException extends \RuntimeException
{
    private array $exceptions;

    public function __construct(string $message, array $exceptions)
    {
        parent::__construct($message);
        $this->exceptions = $exceptions;
    }

    public function getExceptions(): array
    {
        return $this->exceptions;
    }
}
