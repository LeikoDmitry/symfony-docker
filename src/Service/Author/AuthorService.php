<?php

namespace App\Service\Author;

use App\Entity\Book;
use App\Exception\BookAlreadyExistsException;
use App\Model\Author\BookListItem;
use App\Model\Author\BookListResponse;
use App\Model\Author\CreateBookRequest;
use App\Model\Author\PublishBookRequest;
use App\Model\Author\UploadCoverResponse;
use App\Model\IdResponse;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

readonly class AuthorService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private BookRepository $bookRepository,
        private SluggerInterface $slugger,
        private Security $security,
        private UploadedService $uploadedService,
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

    public function publish(int $id, PublishBookRequest $publishBookRequest): void
    {
        $book = $this->bookRepository->getUserBookById($id,  $this->security->getUser());
        $book->setPublicationDate($publishBookRequest->getDateTime());

        $this->entityManager->persist($book);
        $this->entityManager->flush();
    }

    public function unPublish(int $id): void
    {
        $book = $this->bookRepository->getUserBookById($id,  $this->security->getUser());
        $book->setPublicationDate(null);

        $this->entityManager->persist($book);
        $this->entityManager->flush();
    }

    public function uploadCover(int $id, UploadedFile $uploadedFile): UploadCoverResponse
    {
        $link = $this->uploadedService->uploadBookCover($id, $uploadedFile);

        $book = $this->bookRepository->getUserBookById($id,  $this->security->getUser());
        $book->setImage($link);

        $this->entityManager->persist($book);
        $this->entityManager->flush();

        return new UploadCoverResponse(link: $link);
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
