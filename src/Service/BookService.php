<?php

namespace App\Service;

use App\Entity\Book;
use App\Exception\BookCategoryNotFoundException;
use App\Model\BookListItem;
use App\Model\BookListResponse;
use App\Repository\BookCategoryRepository;
use App\Repository\BookRepository;

class BookService
{
    public function __construct(
        private BookRepository $bookRepository,
        private BookCategoryRepository $bookCategoryRepository
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

    private function map(Book $book): BookListItem
    {
        return new BookListItem(
            id: $book->getId(),
            title: $book->getTitle(),
            slug: $book->getSlug(),
            image: $book->getImage(),
            authors: $book->getAuthors(),
            publicationDate: $book->getPublicationDate()->format(\DateTimeInterface::ATOM)
        );
    }
}
