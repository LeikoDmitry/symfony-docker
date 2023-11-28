<?php

namespace App\Exception;

use Throwable;

final class RecommendationRequestException extends RecommendationException
{
    public function __construct(string $message = '', Throwable $throwable = null)
    {
        parent::__construct($message, $throwable);
    }
}
