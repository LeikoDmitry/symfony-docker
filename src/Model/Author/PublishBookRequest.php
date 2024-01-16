<?php

namespace App\Model\Author;

use DateTimeInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class PublishBookRequest
{
    #[NotBlank]
    private DateTimeInterface $dateTime;

    public function setDateTime(DateTimeInterface $dateTime): static
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    public function getDateTime(): DateTimeInterface
    {
        return $this->dateTime;
    }
}
