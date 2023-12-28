<?php

namespace App\Service\Author;

use App\Entity\Book;
use App\Exception\BookAlreadyExistsException;
use App\Model\Author\BookListItem;
use App\Model\Author\BookListResponse;
use App\Model\Author\CreateBookRequest;
use App\Model\IdResponse;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

class AuthorService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private BookRepository $bookRepository,
        private SluggerInterface $slugger,
        private Security $security
    ) {
    }

    public function getBooks(): BookListResponse
    {
        $user = $this->security->getUser();

        return new BookListResponse(items: array_map($this->map(...), $this->bookRepository->findUsersBook($user)));
    }

    public function deleteBook(int $id): void
    {
        $user = $this->security->getUser();
        $book = $this->bookRepository->getUserBookById($id, $user);

        $this->entityManager->remove($book);
        $this->entityManager->flush();
    }

    public function createBook(CreateBookRequest $bookRequest): IdResponse
    {
        $slug = $this->slugger->slug($bookRequest->getTitle());

        if ($this->bookRepository->existBySlug($slug)) {
            throw new BookAlreadyExistsException();
        }

        $book = (new Book())
            ->setTitle($bookRequest->getTitle())
            ->setSlug($slug)
            ->setUser($this->security->getUser()
            );

        $this->entityManager->persist($book);
        $this->entityManager->flush();

        return new IdResponse(id: $book->getId());
    }

    private function map(Book $book): BookListItem
    {
        return new BookListItem(
            id: $book->getId(),
            title: $book->getTitle(),
            slug: $book->getSlug(),
            image: $book->getImage()
        );
    }
}
