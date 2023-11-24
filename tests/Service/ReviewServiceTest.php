<?php

namespace App\Tests\Service;

use App\Entity\Review;
use App\Model\Review as ReviewModel;
use App\Model\ReviewPage;
use App\Repository\ReviewRepository;
use App\Service\RatingService;
use App\Service\ReviewService;
use App\Tests\AbstractTestCase;
use ArrayIterator;
use DateTimeImmutable;

class ReviewServiceTest extends AbstractTestCase
{
    private ReviewRepository $reviewRepository;
    private RatingService $ratingService;
    private const BOOK_ID = 1;

    protected function setUp(): void
    {
        parent::setUp();

        $this->reviewRepository = $this->createMock(ReviewRepository::class);
        $this->ratingService = $this->createMock(RatingService::class);
    }

    /**
     * @return int[][]
     */
    public static function dataProvider(): array
    {
        return [
            [0, 0],
            [-1, 0],
            [-20, 0],
        ];
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetReviewPageByBookIdInvalidPage(int $page, int $offset): void
    {
        $this->ratingService->expects($this->once()) /** @phpstan-ignore-line */
            ->method('calcReview')
            ->with(self::BOOK_ID, 0)
            ->willReturn(0.0);

        $this->reviewRepository->expects($this->once()) /** @phpstan-ignore-line */
            ->method('getPageByBookId')
            ->with(self::BOOK_ID, $offset, 5)
            ->willReturn(new ArrayIterator([]));

        $service = new ReviewService($this->reviewRepository, $this->ratingService);

        $expected = (new ReviewPage())->setTotal(0)->setRating(0)->setPage($page)->setPages(0)->setPerPage(5)->setItems([]);

        $this->assertEquals($expected, $service->getReviewPageByBookId(self::BOOK_ID, $page));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetReviewPageByBookIdSuccess(): void
    {
        $this->ratingService->expects($this->once()) /** @phpstan-ignore-line */
            ->method('calcReview')
            ->with(self::BOOK_ID, 1)
            ->willReturn(4.0);

        $review = (new Review())
            ->setAuthor('test')
            ->setRating(4)
            ->setContent('test content')
            ->setCreatedAt(new DateTimeImmutable('2023-10-10')
            );

        $this->setEntityId($review, 1);

        $this->reviewRepository->expects($this->once()) /** @phpstan-ignore-line */
            ->method('getPageByBookId')
            ->with(self::BOOK_ID, 0, 5)
            ->willReturn(new ArrayIterator([$review]));

        $service = new ReviewService($this->reviewRepository, $this->ratingService);

        $expected = (new ReviewPage())->setTotal(1)->setRating(4)->setPage(1)->setPages(1)->setPerPage(5)->setItems([
            (new ReviewModel())
                ->setRating(4)
                ->setAuthor('test')
                ->setContent('test content')
                ->setCreatedAt((new DateTimeImmutable('2023-10-10'))->format(DATE_ATOM))
                ->setId(1),
        ]);

        $this->assertEquals($expected, $service->getReviewPageByBookId(self::BOOK_ID, 1));
    }
}
