<?php

namespace App\Model\Author;

use Symfony\Component\Validator\Constraints\NotBlank;

readonly class CreateBookRequest
{
    public function __construct(
        #[NotBlank]
        private string $title
    ) {
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
