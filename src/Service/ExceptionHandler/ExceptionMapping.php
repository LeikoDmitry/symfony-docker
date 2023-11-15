<?php

namespace App\Service\ExceptionHandler;

readonly class ExceptionMapping
{
    public function __construct(
        private int $code,
        private bool $hidden,
        private bool $loggable
    ) {
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function isHidden(): bool
    {
        return $this->hidden;
    }

    public function isLoggable(): bool
    {
        return $this->loggable;
    }
}
