<?php

namespace App\Exception;

use RuntimeException;

class BookNotFoundException extends RuntimeException
{
    public function __construct(string $message = 'Book not found')
    {
        parent::__construct($message);
    }
}
