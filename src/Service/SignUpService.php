<?php

namespace App\Service;

use App\Entity\User;
use App\Exception\UserAlreadyExistException;
use App\Model\IdResponse;
use App\Model\SignUpRequest;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SignUpService
{
    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function signUp(SignUpRequest $signUpRequest): IdResponse
    {
        if ($this->userRepository->existByEmail($signUpRequest->getEmail())) {
            throw new UserAlreadyExistException();
        }

        $user = (new User())
            ->setEmail($signUpRequest->getEmail())
            ->setLastname($signUpRequest->getLastname())
            ->setFirstName($signUpRequest->getFirstName()
            );

        $user->setPassword($this->passwordHasher->hashPassword($user, $signUpRequest->getPassword()));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new IdResponse(id: $user->getId());
    }
}
