<?php

namespace App\Service;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

readonly class RoleService
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function grantAdmin(int $userId): void
    {
        $this->grantRole($userId, 'ROLE_ADMIN');
    }

    public function grantAuthor(int $userId): void
    {
        $this->grantRole($userId, 'ROLE_AUTHOR');
    }

    private function grantRole(int $userId, string $role): void
    {
        $user = $this->userRepository->getUser($userId);
        $user->setRoles([$role]);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
