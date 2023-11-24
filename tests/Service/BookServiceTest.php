<?php

namespace App\Tests\Service;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Exception\BookCategoryNotFoundException;
use App\Model\BookListItem;
use App\Model\BookListResponse;
use App\Repository\BookCategoryRepository;
use App\Repository\BookRepository;
use App\Repository\ReviewRepository;
use App\Service\BookService;
use App\Service\RatingService;
use App\Tests\AbstractTestCase;
use DateTime;
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
        $reviewRepository = $this->createMock(ReviewRepository::class);
        $bookCategoryRepository = $this->createMock(BookCategoryRepository::class);
        $ratingService = $this->createMock(RatingService::class);
        $bookCategoryRepository->expects($this->once())
            ->method('find')
            ->with(100)
            ->willReturn(null);

        $this->expectException(BookCategoryNotFoundException::class);

        (new BookService($bookRepository, $bookCategoryRepository, $reviewRepository, $ratingService))->findBooksByCategory(100);
    }

    /**
     * @throws Exception
     */
    public function testFindBooksByCategory(): void
    {
        $book = (new Book())
            ->setTitle('test')
            ->setSlug('test')
            ->setCategories(new ArrayCollection(['android']))
            ->setAuthors(['lorem'])
            ->setImage('default.png')
            ->setIsbn('1234')
            ->setDescription('test description')
            ->setPublicationDate(
                new DateTime('2023-12-12')
            );
        $this->setEntityId(entity: $book, value: 100);

        $reviewRepository = $this->createMock(ReviewRepository::class);
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

        $ratingService = $this->createMock(RatingService::class);

        $service = new BookService(
            bookRepository: $bookRepository,
            bookCategoryRepository: $bookCategoryRepository,
            reviewRepository: $reviewRepository,
            ratingService: $ratingService
        );

        $this->assertEquals(
            new BookListResponse(
                items: [new BookListItem(
                    id: 100,
                    title: 'test',
                    slug: 'test',
                    image: 'default.png',
                    authors: ['lorem'],
                    publicationDate: '2023-12-12T00:00:00+00:00'
                )]
            ),
            $service->findBooksByCategory(100)
        );
    }
}
