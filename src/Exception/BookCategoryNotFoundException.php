<?php

namespace App\Exception;

use RuntimeException;

class BookCategoryNotFoundException extends RuntimeException
{
    public function __construct(string $message = 'Book category not found')
    {
        parent::__construct($message);
    }
}
