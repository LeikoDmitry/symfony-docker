<?php

namespace App\Mapper;

use App\Entity\Book;
use App\Model\BookDetails;
use App\Model\BookListItem;
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
}
