<?php

namespace App\Service;

use App\Entity\User;
use App\Exception\UserAlreadyExistException;
use App\Model\SignUpRequest;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class SignUpService
{
    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
        private EntityManagerInterface $entityManager,
        private AuthenticationSuccessHandler $successHandler
    ) {
    }

    public function signUp(SignUpRequest $signUpRequest): Response
    {
        if ($this->userRepository->existByEmail($signUpRequest->getEmail())) {
            throw new UserAlreadyExistException();
        }

        $user = (new User())
            ->setRoles(['ROLE_USER'])
            ->setEmail($signUpRequest->getEmail())
            ->setLastname($signUpRequest->getLastname())
            ->setFirstName($signUpRequest->getFirstName()
            );

        $user->setPassword($this->passwordHasher->hashPassword($user, $signUpRequest->getPassword()));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->successHandler->handleAuthenticationSuccess($user);
    }
}
