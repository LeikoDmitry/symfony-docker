<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

class BookCategoryNotFoundException extends \RuntimeException
{
    public function __construct(
        string $message = 'Book category not found',
        int $code = Response::HTTP_NOT_FOUND,
        \Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
