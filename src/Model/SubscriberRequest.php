<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

readonly class SubscriberRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Email]
        public string $email,

        #[Assert\NotBlank]
        #[Assert\IsTrue]
        public bool $agreed,
    ) {
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function isAgreed(): bool
    {
        return $this->agreed;
    }
}
