<?php

declare(strict_types=1);

namespace Inverse\Termin\Exceptions;

class InvalidResponseException extends TerminException
{
    public function __construct(
        string $message,
        private readonly string $url,
        private readonly int $statusCode,
    ) {
        parent::__construct($message);
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
