<?php

namespace App\Listener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JWTCreatedListener
{
    public function __invoke(JWTCreatedEvent $createdEvent)
    {
        $user = $createdEvent->getUser();
        $payload = $createdEvent->getData();
        $payload['id'] = $user->getUserIdentifier();

        $createdEvent->setData($payload);
    }
}
