<?php

namespace App\Tests\Service;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Entity\BookFormat;
use App\Entity\BookRelationToBookFormat;
use App\Exception\BookCategoryNotFoundException;
use App\Model\BookDetails;
use App\Model\BookFormatListItem;
use App\Model\BookListItem;
use App\Model\BookListRecommendationResponse;
use App\Model\BookListResponse;
use App\Model\Recommendation\RecommendationItem;
use App\Model\Recommendation\RecommendationResponse;
use App\Model\RecommendedBook;
use App\Repository\BookCategoryRepository;
use App\Repository\BookRepository;
use App\Repository\ReviewRepository;
use App\Service\BookService;
use App\Service\RatingService;
use App\Service\Recommendation\RecommendationService;
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
        $recommendationService = $this->createMock(RecommendationService::class);
        $bookCategoryRepository->expects($this->once())
            ->method('find')
            ->with(100)
            ->willReturn(null);

        $this->expectException(BookCategoryNotFoundException::class);

        (new BookService($bookRepository, $bookCategoryRepository, $reviewRepository, $ratingService, $recommendationService))->findBooksByCategory(100);
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
        $recommendationService = $this->createMock(RecommendationService::class);

        $service = new BookService(
            bookRepository: $bookRepository,
            bookCategoryRepository: $bookCategoryRepository,
            reviewRepository: $reviewRepository,
            ratingService: $ratingService,
            recommendationService: $recommendationService
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

    public function testGetBookById(): void
    {
        $bookFormat = new BookFormatListItem();
        $bookFormat
            ->setId(1)
            ->setTitle('format')
            ->setDescription('test')
            ->setComment('test')
            ->setPrice(78.0)
            ->setDiscountPercent(8)
        ;
        $format = (new BookFormat())
            ->setTitle('format')
            ->setDescription('test')
            ->setComment('test');
        $this->setEntityId($format, value: 1);

        $join = (new BookRelationToBookFormat())->setDiscountPercent(8)->setFormat($format)->setPrice(78);
        $this->setEntityId($join, value: 1);

        $book = (new Book())
            ->setTitle('test')
            ->setSlug('test')
            ->setCategories(new ArrayCollection([]))
            ->setFormats(new ArrayCollection([$join]))
            ->setAuthors(['lorem'])
            ->setImage('test')
            ->setIsbn('1234')
            ->setDescription('test description')
            ->setPublicationDate(
                new DateTime('2023-12-12')
            );

        $this->setEntityId(entity: $book, value: 100);

        $bookRepository = $this->createMock(BookRepository::class);
        $bookRepository->expects($this->once())
            ->method('find')
            ->with(100)
            ->willReturn($book);

        $ratingService = $this->createMock(RatingService::class);
        $ratingService->expects($this->once())->method('calcReview')->with(100)->willReturn(1.25);

        $bookCategoryRepository = $this->createMock(BookCategoryRepository::class);
        $reviewRepository = $this->createMock(ReviewRepository::class);
        $recommendationService = $this->createMock(RecommendationService::class);

        $service = new BookService(
            bookRepository: $bookRepository,
            bookCategoryRepository: $bookCategoryRepository,
            reviewRepository: $reviewRepository,
            ratingService: $ratingService,
            recommendationService: $recommendationService
        );

        $expected = (new BookDetails(
            id: 100,
            title: 'test',
            slug: 'test',
            image: 'test',
            authors: ['lorem'],
            publicationDate: '2023-12-12T00:00:00+00:00',
            rating: 1.25,
            review: 0,
            categories: [],
            formats: [$bookFormat]
        ));

        $this->assertEquals($expected, $service->getBookById($book->getId()));
    }

    public static function dataProvider(): array /* @phpstan-ignore-line */
    {
        return [
            ['short description', 'short description'],
            [
                <<<EOF
                    urabitur facilisis consequat quam, nec venenatis leo pellentesque eget.
                    Donec placerat pellentesque libero, non mollis justo egestas nec.
                    Aenean vehicula, risus a congue dapibus, ipsum eros interdum purus,
                    vel mollis risus ligula sed neque. Phasellus euismod congue ullamcorper.
                    Vivamus molestie tellus aliquam, aliquet leo at, efficitur quam.
                    Donec vulputate leo quis aliquam blandit. Curabitur varius ultricies ex sed sagittis.
                    Suspendisse bibendum gravida ornare
                 EOF,
                <<<EOF
                    urabitur facilisis consequat quam, nec venenatis leo pellentesque eget.
                    Donec placerat pellentesque libero, non mollis justo egestas nec.
                    Aen...
                 EOF,
            ],
        ];
    }

    /**
     * @dataProvider dataProvider
     */
    public function testFindBooksByRecommendations(string $actualDescription, string $expectedDescription): void
    {
        $bookCategoryRepository = $this->createMock(BookCategoryRepository::class);
        $reviewRepository = $this->createMock(ReviewRepository::class);
        $ratingService = $this->createMock(RatingService::class);

        $book = (new Book())
            ->setTitle('test')
            ->setSlug('test')
            ->setCategories(new ArrayCollection([]))
            ->setFormats(new ArrayCollection([]))
            ->setAuthors(['lorem'])
            ->setImage('test')
            ->setIsbn('1234')
            ->setDescription($actualDescription)
            ->setPublicationDate(
                new DateTime('2023-12-12')
            );

        $this->setEntityId($book, 5);

        $bookRepository = $this->createMock(BookRepository::class);
        $bookRepository->expects($this->once())
            ->method('findBooksByIds')
            ->with([5])
            ->willReturn([$book]);

        $recommendationService = $this->createMock(RecommendationService::class);
        $recommendationService->expects($this->once())
            ->method('getRecommendationByBookId')
            ->with(6)->willReturn(new RecommendationResponse(
                id: 5,
                ts: 12245678,
                recommendations: [
                    new RecommendationItem(id: 5),
                ])
            );

        $service = new BookService(
            bookRepository: $bookRepository,
            bookCategoryRepository: $bookCategoryRepository,
            reviewRepository: $reviewRepository,
            ratingService: $ratingService,
            recommendationService: $recommendationService
        );

        $response = $service->findBooksByRecommendations(6);

        $expected = new BookListRecommendationResponse(items: [
            (new RecommendedBook())
                ->setDescription($expectedDescription)
                ->setId(5)
                ->setTitle('test')
                ->setSlug('test'),
        ]);

        $this->assertEquals($expected, $response);
    }
}
