<?php

namespace App\Listener;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JWTCreatedListener
{
    public function __invoke(JWTCreatedEvent $createdEvent): void
    {
        /** @var User $user */
        $user = $createdEvent->getUser();
        $payload = $createdEvent->getData();
        $payload['id'] = $user->getId();

        $createdEvent->setData($payload);
    }
}
