<?php

namespace App\Model;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;

readonly class ErrorResponse
{
    public function __construct(private string $message, private mixed $details = null)
    {
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    #[OA\Property(type: 'object', oneOf: [new OA\Schema(ref: new Model(type: ErrorDebugDetails::class))])]
    public function getDetails(): mixed
    {
        return $this->details;
    }
}
