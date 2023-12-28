<?php

namespace App\Exception;

use RuntimeException;

class BookAlreadyExistsException extends RuntimeException
{
    public function __construct(string $message = 'Book already exist!')
    {
        parent::__construct($message);
    }
}
