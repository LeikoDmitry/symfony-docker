<?php

namespace App\Model;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;

readonly class ErrorResponse
{
    public function __construct(private string $message, private mixed $details = null)
    {
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @OA\Property(type="object", oneOf={@OA\Schema(ref=@Model(type=ErrorDebugDetails::class))})
     */
    public function getDetails(): mixed
    {
        return $this->details;
    }
}
