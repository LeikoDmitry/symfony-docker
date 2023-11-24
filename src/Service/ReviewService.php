<?php

namespace App\Service;

use App\Entity\Review;
use App\Model\Review as ReviewModel;
use App\Model\ReviewPage;
use App\Repository\ReviewRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Exception;

class ReviewService
{
    private const PAGE_LIMIT = 5;

    public function __construct(
        private readonly ReviewRepository $reviewRepository,
        private readonly RatingService $ratingService
    ) {
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     * @throws Exception
     */
    public function getReviewPageByBookId(int $id, int $page): ReviewPage
    {
        $offset = max($page - 1, 0) * self::PAGE_LIMIT;
        $paginator = $this->reviewRepository->getPageByBookId(id: $id, offset: $offset, limit: self::PAGE_LIMIT);
        $total = count($paginator);
        $items = [];

        foreach ($paginator as $item) {
            $items[] = $this->map($item);
        }

        return (new ReviewPage())
            ->setRating($this->ratingService->calcReview($id, $total))
            ->setTotal($total)
            ->setPage($page)
            ->setPerPage(self::PAGE_LIMIT)
            ->setPages((int) ceil($total / self::PAGE_LIMIT))
            ->setItems($items)
        ;
    }

    public function map(Review $review): ReviewModel
    {
        return (new ReviewModel())
            ->setId($review->getId())
            ->setRating($review->getRating())
            ->setCreatedAt($review->getCreatedAt()->format(DATE_ATOM))
            ->setAuthor($review->getAuthor())
            ->setContent($review->getContent());
    }
}
