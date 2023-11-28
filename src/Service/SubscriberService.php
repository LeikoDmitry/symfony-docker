<?php

namespace App\Service;

use App\Entity\Subscriber;
use App\Exception\SubscriberFoundException;
use App\Model\SubscriberRequest;
use App\Repository\SubscriberRepository;
use Doctrine\ORM\EntityManagerInterface;

readonly class SubscriberService
{
    public function __construct(
        private SubscriberRepository $subscriberRepository,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function subscribe(SubscriberRequest $subscriberRequest): void
    {
        $subscriber = $this->subscriberRepository->findOneBy(['email' => $subscriberRequest->getEmail()]);

        if ($subscriber) {
            throw new SubscriberFoundException();
        }

        $this->entityManager->persist((new Subscriber())->setEmail($subscriberRequest->getEmail()));
        $this->entityManager->flush();
    }
}
