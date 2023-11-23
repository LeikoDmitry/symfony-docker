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

    public function __construct(private ReviewRepository $reviewRepository)
    {
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     * @throws Exception
     */
    public function getReviewPageByBookId(int $id, int $page): ReviewPage
    {
        $offset = max($page - 1, 0) * self::PAGE_LIMIT; /** @phpstan-ignore-line */
        $paginator = $this->reviewRepository->getPageByBookId(id: $id, offset: $offset, limit: self::PAGE_LIMIT);
        $total = count($paginator);
        $reviews = $this->reviewRepository->getBookTotalRatingSum($id);

        return (new ReviewPage())
            ->setRating($total > 0 ? $reviews / $total : 0)
            ->setTotal($total)
            ->setPage($page)
            ->setPerPage(self::PAGE_LIMIT)
            ->setPages((int) ceil($total / self::PAGE_LIMIT))
            ->setItems(array_map(callback: $this->map(...), array: iterator_to_array($paginator->getIterator())))
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
