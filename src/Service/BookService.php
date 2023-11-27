<?php

namespace App\Service;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Entity\BookRelationToBookFormat;
use App\Exception\BookCategoryNotFoundException;
use App\Exception\BookNotFoundException;
use App\Mapper\BookMapper;
use App\Model\BookCategoryListItem;
use App\Model\BookDetails;
use App\Model\BookFormatListItem;
use App\Model\BookListItem;
use App\Model\BookListRecommendationResponse;
use App\Model\BookListResponse;
use App\Model\Recommendation\RecommendationItem;
use App\Repository\BookCategoryRepository;
use App\Repository\BookRepository;
use App\Repository\ReviewRepository;
use App\Service\Recommendation\RecommendationService;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

readonly class BookService
{
    public function __construct(
        private BookRepository $bookRepository,
        private BookCategoryRepository $bookCategoryRepository,
        private ReviewRepository $reviewRepository,
        private RatingService $ratingService,
        private RecommendationService $recommendationService
    ) {
    }

    public function findBooksByCategory(int $categoryId): BookListResponse
    {
        $category = $this->bookCategoryRepository->find($categoryId);

        if (!$category) {
            throw new BookCategoryNotFoundException();
        }

        return new BookListResponse(
            array_map(
                callback: function (Book $book) {
                    return BookMapper::map($book, BookListItem::class);
                },
                array: $this->bookRepository->findByCategory($categoryId))
        );
    }

    public function findBooksByRecommendations(int $idBook): BookListRecommendationResponse
    {
        $ids = array_map(
            callback: function (RecommendationItem $recommendationItem) {
                return $recommendationItem->getId();
            },
            array: $this->recommendationService->getRecommendationByBookId($idBook)->getRecommendations()
        );

        return new BookListRecommendationResponse(
            array_map([BookMapper::class, 'mapRecommendations'], $this->bookRepository->findBooksByIds($ids))
        );
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function getBookById(int $id): BookDetails
    {
        $book = $this->bookRepository->find($id);

        if (!$book) {
            throw new BookNotFoundException();
        }

        $reviews = $this->reviewRepository->countByBook($id);

        $formats = $book->getFormats()
            ->map(func: function (BookRelationToBookFormat $formatJoin) {
                return (new BookFormatListItem())
                    ->setId($formatJoin->getFormat()->getId())
                    ->setTitle($formatJoin->getFormat()->getTitle())
                    ->setDescription($formatJoin->getFormat()->getDescription())
                    ->setComment($formatJoin->getFormat()->getComment())
                    ->setPrice($formatJoin->getPrice())
                    ->setDiscountPercent($formatJoin->getDiscountPercent());
            });

        $categories = $book->getCategories()
            ->map(func: function (BookCategory $bookCategory) {
                return new BookCategoryListItem(
                    id: $bookCategory->getId(),
                    title: $bookCategory->getTitle(),
                    slug: $bookCategory->getSlug());
            });

        return BookMapper::map($book, BookDetails::class)
            ->setReview($reviews)
            ->setCategories($categories->toArray())
            ->setFormats($formats->toArray())
            ->setRating($this->ratingService->calcReview($id, $reviews)
            );
    }
}
