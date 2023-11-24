<?php

namespace App\Tests\Service;

use App\Repository\ReviewRepository;
use App\Service\RatingService;
use App\Tests\AbstractTestCase;

class RatingServiceTest extends AbstractTestCase
{
    private ReviewRepository $reviewRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->reviewRepository = $this->createMock(ReviewRepository::class);
    }

    /**
     * @return int[][]
     */
    public static function provider(): array
    {
        return [
            [25, 20, 1.25],
            [0, 5, 0],
        ];
    }

    /**
     * @dataProvider provider
     */
    public function testCalcReview(int $repositoryRatingSum, int $total, float $expectedRating): void
    {
        $this->reviewRepository->expects($this->once()) /** @phpstan-ignore-line */
            ->method('getBookTotalRatingSum')
            ->with(1)
            ->willReturn($repositoryRatingSum);
        $this->assertEquals(
            $expectedRating,
            (new RatingService($this->reviewRepository))->calcReview(1, $total)
        );
    }

    public function testCalcReviewZeroTotal(): void
    {
        $this->reviewRepository->expects($this->never())->method('getBookTotalRatingSum'); /** @phpstan-ignore-line */

        $this->assertEquals(0, (new RatingService($this->reviewRepository))->calcReview(1, 0));
    }
}
