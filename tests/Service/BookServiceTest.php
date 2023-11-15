<?php

namespace App\Tests\Service;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Exception\BookCategoryNotFoundException;
use App\Model\BookListItem;
use App\Model\BookListResponse;
use App\Repository\BookCategoryRepository;
use App\Repository\BookRepository;
use App\Service\BookService;
use App\Tests\AbstractTestCase;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\Exception;

class BookServiceTest extends AbstractTestCase
{
    /**
     * @throws Exception
     */
    public function testFindBooksByCategoryNotFound(): void
    {
        $bookRepository = $this->createMock(BookRepository::class);
        $bookCategoryRepository = $this->createMock(BookCategoryRepository::class);
        $bookCategoryRepository->expects($this->once())
            ->method('find')
            ->with(100)
            ->willReturn(null);

        $this->expectException(BookCategoryNotFoundException::class);

        (new BookService($bookRepository, $bookCategoryRepository))->findBooksByCategory(100);
    }

    /**
     * @throws Exception
     */
    public function testFindBooksByCategory(): void
    {
        $book = (new Book())
            ->setTitle('test')
            ->setSlug('test')
            ->setMeap(true)
            ->setCategories(new ArrayCollection(['android']))
            ->setAuthors(['lorem'])
            ->setImage('default.png')
            ->setPublicationDate(new \DateTime('2023-12-12')
            );
        $this->setEntityId(entity: $book, value: 100);

        $bookRepository = $this->createMock(BookRepository::class);
        $bookRepository->expects($this->once())
            ->method('findByCategory')
            ->with(100)
            ->willReturn([$book]);

        $bookCategoryRepository = $this->createMock(BookCategoryRepository::class);
        $bookCategoryRepository->expects($this->once())
            ->method('find')
            ->with(100)
            ->willReturn(new BookCategory());

        $service = new BookService(bookRepository: $bookRepository, bookCategoryRepository: $bookCategoryRepository);

        $this->assertEquals(
            new BookListResponse(items: [new BookListItem(
                id: 100,
                title: 'test',
                slug: 'test',
                image: 'default.png',
                authors: ['lorem'],
                publicationDate: '2023-12-12T00:00:00+00:00')]
            ),
            $service->findBooksByCategory(100));
    }
}
