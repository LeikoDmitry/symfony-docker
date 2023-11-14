<?php

namespace App\Exception;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class BookCategoryNotFoundException extends RuntimeException
{
    public function __construct(
        string $message = 'Book category not found',
        int $code = Response::HTTP_NOT_FOUND,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
