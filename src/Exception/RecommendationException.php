<?php

namespace App\Exception;

use Exception;
use PHPUnit\Event\Code\Throwable;

class RecommendationException extends Exception
{
    public function __construct(string $message = '', ?Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
