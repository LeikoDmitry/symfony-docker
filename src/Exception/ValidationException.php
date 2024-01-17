<?php

namespace App\Exception;

use RuntimeException;
use Symfony\Component\Validator\ConstraintViolationList;

class ValidationException extends RuntimeException
{
    private ConstraintViolationList $violations;

    public function __construct(ConstraintViolationList $violationList)
    {
        $this->violations = $violationList;
        parent::__construct('Validation error: '.json_encode($this->getMessages()));
    }

    public function getMessages(): array
    {
        $messages = [];
        foreach ($this->violations as $paramName => $violationList) {
            $messages[$paramName] = $violationList->getMessage();
        }

        return $messages;
    }
}
