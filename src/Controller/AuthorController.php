<?php

namespace App\Controller;

use App\Attribute\RequestFile;
use App\Model\Author\BookListResponse;
use App\Model\Author\CreateBookRequest;
use App\Model\Author\PublishBookRequest;
use App\Model\Author\UpdateBookRequest;
use App\Model\Author\UploadCoverResponse;
use App\Model\ErrorResponse;
use App\Service\Author\AuthorService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotNull;

class AuthorController extends AbstractController
{
    public function __construct(private readonly AuthorService $authorService)
    {
    }

    #[OA\Tag(name: 'Author API')]
    #[OA\Response(response: 200, description: 'Get authors owned books', content: new Model(type: BookListResponse::class))]
    #[Route(path: '/api/v1/author/books', name: 'author_books', methods: 'GET')]
    public function books(): Response
    {
        return $this->json($this->authorService->getBooks());
    }

    #[OA\Tag(name: 'Author API')]
    #[OA\Response(response: 200, description: 'Create a book')]
    #[OA\Response(response: 422, description: 'Validation Error', content: new Model(type: ErrorResponse::class))]
    #[OA\RequestBody(content: new Model(type: CreateBookRequest::class))]
    #[Route(path: '/api/v1/author/books', name: 'author_books_create', methods: 'POST')]
    public function createBook(#[MapRequestPayload] CreateBookRequest $bookRequest): Response
    {
        return $this->json($this->authorService->createBook($bookRequest));
    }

    #[OA\Tag(name: 'Author API')]
    #[OA\Response(response: 200, description: 'Remove a book')]
    #[OA\Response(response: 404, description: 'Book not found', content: new Model(type: ErrorResponse::class))]
    #[Route(path: '/api/v1/author/books/{id}', name: 'author_books_delete', methods: 'DELETE')]
    public function deleteBook(int $id): Response
    {
        $this->authorService->deleteBook($id);

        return $this->json(null);
    }

    #[OA\Tag(name: 'Author API')]
    #[OA\Response(response: 200, description: 'Update a book')]
    #[OA\Response(response: 404, description: 'Book not found', content: new Model(type: ErrorResponse::class))]
    #[Route(path: '/api/v1/author/books/{id}', name: 'author_books_update', methods: 'POST')]
    public function updateBook(int $id, #[MapRequestPayload] UpdateBookRequest $updateBookRequest): Response
    {
        $this->authorService->updateBook($id, $updateBookRequest);

        return $this->json(null);
    }

    #[OA\Tag(name: 'Author API')]
    #[OA\Response(response: 200, description: 'Publish a book')]
    #[OA\Response(response: 422, description: 'Validation Error', content: new Model(type: ErrorResponse::class))]
    #[OA\RequestBody(content: new Model(type: PublishBookRequest::class))]
    #[Route(path: '/api/v1/author/books/publish/{id}', name: 'author_books_publish', methods: 'POST')]
    public function publish(int $id, #[MapRequestPayload] PublishBookRequest $bookRequest): Response
    {
        $this->authorService->publish(id: $id, publishBookRequest: $bookRequest);

        return $this->json(null);
    }

    #[OA\Tag(name: 'Author API')]
    #[OA\Response(response: 200, description: 'Un publish a book')]
    #[OA\Response(response: 422, description: 'Validation Error', content: new Model(type: ErrorResponse::class))]
    #[Route(path: '/api/v1/author/books/un-publish/{id}', name: 'author_books_un_publish', methods: 'POST')]
    public function unPublish(int $id): Response
    {
        $this->authorService->unPublish(id: $id);

        return $this->json(null);
    }

    #[OA\Tag(name: 'Author API')]
    #[OA\Response(response: 200, description: 'Upload book cover', content: new Model(type: UploadCoverResponse::class))]
    #[OA\Response(response: 422, description: 'Validation Error', content: new Model(type: ErrorResponse::class))]
    #[Route(path: '/api/v1/author/books/upload-cover/{id}', name: 'author_books_cover', methods: 'POST')]
    public function uploadCover(int $id, #[RequestFile(field: 'cover', constraints: [
        new NotNull(),
        new Image(maxSize: '1M', mimeTypes: ['image/jpeg', 'image/png', 'image/jpg']),
    ])] UploadedFile $uploadedFile): Response
    {
        $response = $this->authorService->uploadCover(id: $id, uploadedFile: $uploadedFile);

        return $this->json($response);
    }
}
