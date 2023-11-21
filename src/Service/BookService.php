<?php

namespace App\Service;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Entity\BookRelationToBookFormat;
use App\Exception\BookCategoryNotFoundException;
use App\Exception\BookNotFoundException;
use App\Model\BookCategoryListItem;
use App\Model\BookDetails;
use App\Model\BookFormatListItem;
use App\Model\BookListItem;
use App\Model\BookListResponse;
use App\Repository\BookCategoryRepository;
use App\Repository\BookRepository;
use App\Repository\ReviewRepository;
use DateTimeInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class BookService
{
    public function __construct(
        private readonly BookRepository $bookRepository,
        private readonly BookCategoryRepository $bookCategoryRepository,
        private readonly ReviewRepository $reviewRepository
    ) {
    }

    public function findBooksByCategory(int $categoryId): BookListResponse
    {
        $category = $this->bookCategoryRepository->find($categoryId);

        if (!$category) {
            throw new BookCategoryNotFoundException();
        }

        return new BookListResponse(
            array_map(callback: $this->map(...), array: $this->bookRepository->findByCategory($categoryId))
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

        $reviews = $this->reviewRepository->count(['book' => $id]);
        $ratingSum = $this->reviewRepository->getBookTotalRatingSum($id);

        $formats = $book->getFormat()
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

        return new BookDetails(
            id: $book->getId(),
            title: $book->getTitle(),
            slug: $book->getSlug(),
            image: $book->getImage(),
            authors: $book->getAuthors(),
            publicationDate: $book->getPublicationDate()->format(DateTimeInterface::ATOM),
            rating: ($ratingSum / $reviews),
            review: $reviews,
            categories: $categories->toArray(),
            formats: $formats->toArray()
        );
    }

    private function map(Book $book): BookListItem
    {
        return new BookListItem(
            id: $book->getId(),
            title: $book->getTitle(),
            slug: $book->getSlug(),
            image: $book->getImage(),
            authors: $book->getAuthors(),
            publicationDate: $book->getPublicationDate()->format(DateTimeInterface::ATOM)
        );
    }
}
