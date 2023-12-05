<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Exception\UserAlreadyExistException;
use App\Model\SignUpRequest;
use App\Repository\UserRepository;
use App\Service\SignUpService;
use App\Tests\AbstractTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;

class SignUpServiceTest extends AbstractTestCase
{
    private UserPasswordHasher $passwordHasher;
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;
    private AuthenticationSuccessHandler $authenticationSuccessHandler;

    public function setUp(): void
    {
        parent::setUp();

        $this->passwordHasher = $this->createMock(UserPasswordHasher::class);
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->authenticationSuccessHandler = $this->createMock(AuthenticationSuccessHandler::class);
    }

    public function testSignUpUserAlreadyExist()
	{
        $this->expectException(UserAlreadyExistException::class);

        $this->userRepository->expects($this->once())
            ->method('existByEmail')
            ->with('test@email.ru')
            ->willReturn(true);

        $service = new SignUpService(
            userRepository: $this->userRepository,
            passwordHasher: $this->passwordHasher,
            entityManager: $this->entityManager,
            successHandler: $this->authenticationSuccessHandler
        );

        $service->signUp((new SignUpRequest())->setEmail('test@email.ru'));
    }

    public function testSignUp()
    {
        $response = new Response();
        $expectedHasherUser =  $user = (new User())
            ->setRoles(['ROLE_USER'])
            ->setEmail('test@gmail.com')
            ->setLastname('test')
            ->setFirstName('test');

        $expectedUser = clone $expectedHasherUser;
        $expectedUser->setPassword('hashPassword');

        $this->userRepository->expects($this->once())
            ->method('existByEmail')
            ->with('test@gmail.com')
            ->willReturn(false);

        $this->passwordHasher->expects($this->once())
            ->method('hashPassword')
            ->with($expectedHasherUser, 'testTest')
            ->willReturn('hashPassword');

        $this->entityManager->expects($this->once())->method('persist')->with($expectedUser);
        $this->entityManager->expects($this->once())->method('flush');

        $this->authenticationSuccessHandler->expects($this->once())
            ->method('handleAuthenticationSuccess')
            ->with($expectedUser)
            ->willReturn($response);

        $service = new SignUpService(
            userRepository: $this->userRepository,
            passwordHasher: $this->passwordHasher,
            entityManager: $this->entityManager,
            successHandler: $this->authenticationSuccessHandler
        );

        $this->assertEquals($response, $service->signUp((new SignUpRequest())
            ->setEmail('test@gmail.com')
            ->setLastname('test')
            ->setFirstName('test')
            ->setPassword('testTest')
            ->setConfirmPassword('testTest')
        ));
    }
}
