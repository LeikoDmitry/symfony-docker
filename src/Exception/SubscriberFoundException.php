<?php

namespace App\Exception;

use RuntimeException;

class SubscriberFoundException extends RuntimeException
{
    public function __construct(string $message = 'Subscriber already exist!')
    {
        parent::__construct($message);
    }
}
