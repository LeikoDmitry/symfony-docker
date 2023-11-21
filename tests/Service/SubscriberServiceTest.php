<?php

namespace App\Tests\Service;

use App\Entity\Subscriber;
use App\Exception\SubscriberFoundException;
use App\Model\SubscriberRequest;
use App\Repository\SubscriberRepository;
use App\Service\SubscriberService;
use App\Tests\AbstractTestCase;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\Exception;

class SubscriberServiceTest extends AbstractTestCase
{
    private SubscriberRepository $subscriberRepository;
    private EntityManagerInterface $entityManager;
    protected const EMAIL = 'test@mail.com';

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->subscriberRepository = $this->createMock(SubscriberRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
    }

    public function testSubscribeAlreadyExist(): void
    {
        $this->expectException(SubscriberFoundException::class);

        $this->subscriberRepository->expects($this->once()) /* @phpstan-ignore-line */
            ->method('findOneBy')
            ->with(['email' => static::EMAIL])
            ->willReturn((new Subscriber())->setEmail(static::EMAIL)->setId(1));

        (new SubscriberService($this->subscriberRepository, $this->entityManager))
            ->subscribe(new SubscriberRequest(email: static::EMAIL, agreed: false));
    }

    public function testSubscribe(): void
    {
        $this->subscriberRepository->expects($this->once()) /* @phpstan-ignore-line */
            ->method('findOneBy')
            ->with(['email' => static::EMAIL])
            ->willReturn(null);

        $expectedSubscriber = new Subscriber();
        $expectedSubscriber->setEmail(static::EMAIL);

        $this->entityManager->expects($this->once())->method('persist')->with($expectedSubscriber); /* @phpstan-ignore-line */
        $this->entityManager->expects($this->once())->method('flush'); /* @phpstan-ignore-line */

        (new SubscriberService($this->subscriberRepository, $this->entityManager))
            ->subscribe(new SubscriberRequest(email: static::EMAIL, agreed: false));
    }
}
