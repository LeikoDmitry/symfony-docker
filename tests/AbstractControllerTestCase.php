<?php

namespace App\Tests;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Helmich\JsonAssert\JsonAssertions;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AbstractControllerTestCase extends WebTestCase
{
    use JsonAssertions;

    protected KernelBrowser $kernelBrowser;
    protected ?EntityManagerInterface $entityManager;
    protected UserPasswordHasherInterface $passwordHasher;

    protected function setUp(): void
    {
        parent::setUp();

        $this->kernelBrowser = static::createClient();
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $this->passwordHasher = self::getContainer()->get('security.user_password_hasher');
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }

    /**
     * @param string[] $roles
     */
    protected function createUser(string $username, string $password, array $roles = []): User
    {
        $user = (new User())
            ->setLastname($username)
            ->setFirstName($username)
            ->setEmail($username)
            ->setPassword($password)
            ->setRoles($roles);

        $user->setPassword($this->passwordHasher->hashPassword($user, $password));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    protected function auth(string $username, string $password): void
    {
        $this->kernelBrowser->request(
            method: 'POST',
            uri: '/api/v1/auth/login',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode(['username' => $username, 'password' => $password])
        );

        $this->assertResponseIsSuccessful();

        $data = json_decode($this->kernelBrowser->getResponse()->getContent(), true);

        $this->kernelBrowser->setServerParameter(
            'HTTP_Authorization', sprintf('Bearer %s', $data['token'])
        );
    }
}
