<?php

namespace App\Mapper;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Entity\BookRelationToBookFormat;
use App\Model\BookCategoryListItem;
use App\Model\BookDetails;
use App\Model\Author\BookDetails as AuthorBookDetails;
use App\Model\BookFormatListItem;
use App\Model\BookListItem;
use App\Model\RecommendedBook;
use DateTimeInterface;

class BookMapper
{
    public static function map(Book $book, string $viewModel): BookDetails|BookListItem|AuthorBookDetails
    {
        $publicationDate = $book->getPublicationDate();

        if ($publicationDate) {
            $publicationDate = $publicationDate->format(DateTimeInterface::ATOM);
        }

        return new $viewModel(
            id: $book->getId(),
            title: $book->getTitle(),
            slug: $book->getSlug(),
            image: $book->getImage(),
            authors: $book->getAuthors(),
            publicationDate: $publicationDate,
        );
    }

    public static function mapCategories(Book $book): array
    {
        return $book->getCategories()
            ->map(func: function (BookCategory $bookCategory) {
                return new BookCategoryListItem(
                    id: $bookCategory->getId(),
                    title: $bookCategory->getTitle(),
                    slug: $bookCategory->getSlug());
            })->toArray();
    }

    public static function mapFormats(Book $book): array
    {
        return $book->getFormats()
            ->map(func: function (BookRelationToBookFormat $formatJoin) {
                return (new BookFormatListItem())
                    ->setId($formatJoin->getFormat()->getId())
                    ->setTitle($formatJoin->getFormat()->getTitle())
                    ->setDescription($formatJoin->getFormat()->getDescription())
                    ->setComment($formatJoin->getFormat()->getComment())
                    ->setPrice($formatJoin->getPrice())
                    ->setDiscountPercent($formatJoin->getDiscountPercent());
            })->toArray();
    }

    public static function mapRecommendations(Book $book): RecommendedBook
    {
        $description = (string) $book->getDescription();
        $description = mb_strlen($description) > 150 ? mb_substr($description, 0, 150).'...' : $description;

        return (new RecommendedBook())
            ->setId($book->getId())
            ->setTitle($book->getTitle())
            ->setSlug($book->getSlug())
            ->setDescription($description)
        ;
    }
}
