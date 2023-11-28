<?php

namespace App\Exception;

use Exception;
use Throwable;

class RecommendationAccessDeniedException extends Exception
{
    public function __construct(string $message = 'Access Denied', Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
