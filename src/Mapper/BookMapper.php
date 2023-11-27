<?php

namespace App\Mapper;

use App\Entity\Book;
use App\Model\BookDetails;
use App\Model\BookListItem;
use App\Model\RecommendedBook;
use DateTimeInterface;

class BookMapper
{
    public static function map(Book $book, string $viewModel): BookDetails|BookListItem
    {
        return new $viewModel(
            id: $book->getId(),
            title: $book->getTitle(),
            slug: $book->getSlug(),
            image: $book->getImage(),
            authors: $book->getAuthors(),
            publicationDate: $book->getPublicationDate()->format(DateTimeInterface::ATOM),
        );
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
