<?php

namespace App\Tests\Repository;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Repository\BookRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BookRepositoryTest extends KernelTestCase
{
    private BookRepository $bookRepository;
    protected ?EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->bookRepository = $this->getRepositoryForEntity(Book::class);
    }

    public function testFindByCategory(): void
    {
        $deviceCategory = (new BookCategory())->setTitle('Devices')->setSlug('devices');
        $this->entityManager->persist($deviceCategory);

        for ($i = 0; $i < 5; ++$i) {
            $book = $this->createBook('device'.$i, $deviceCategory);
            $this->entityManager->persist($book);
        }

        $this->entityManager->flush();

        $this->assertCount(5, $this->bookRepository->findByCategory($deviceCategory->getId()));
    }

    private function createBook(string $name, BookCategory $bookCategory): Book
    {
        return (new Book())
            ->setPublicationDate(new DateTimeImmutable())
            ->setAuthors(['author'])
            ->setMeap(false)
            ->setSlug($name)
            ->setImage('test.png')
            ->setTitle('test')
            ->setCategories(new ArrayCollection([$bookCategory]));
    }

    protected function getRepositoryForEntity(string $entityClass): ?object
    {
        return $this->entityManager->getRepository($entityClass); /* @phpstan-ignore-line */
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}
