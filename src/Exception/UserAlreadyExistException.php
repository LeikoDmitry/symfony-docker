<?php

namespace App\Exception;

use RuntimeException;

class UserAlreadyExistException extends RuntimeException
{
    public function __construct(string $message = 'User already exist')
    {
        parent::__construct($message);
    }
}
