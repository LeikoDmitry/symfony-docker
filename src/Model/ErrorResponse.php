<?php

namespace App\Model;

use OpenApi\Annotations as OA;

class ErrorResponse
{
    public function __construct(private readonly string $message, private mixed $details = null)
    {
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @OA\Property(type="object")
     */
    public function getDetails() /* @phpstan-ignore-line */
    {
        return $this->details;
    }
}
