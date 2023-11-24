<?php

namespace App\Controller;

use App\Model\BookDetails;
use App\Model\BookListResponse;
use App\Model\ReviewPage;
use App\Service\BookService;
use App\Service\ReviewService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{

    public function __construct(
        private readonly BookService $bookCategoryService,
        private readonly ReviewService $reviewService
    ) {
    }
    #[OA\Response(response: 200, description: 'Return book by category id', content: new Model(type: BookListResponse::class))]
    #[OA\Response(response: 404, description: 'Book category not found')]
    #[Route(path: '/api/v1/books/category/{id}', methods: 'GET|OPTIONS')]
    public function booksByCategory(int $id): Response
    {
        return $this->json($this->bookCategoryService->findBooksByCategory($id));
    }

    #[OA\Response(response: 200, description: 'Return book details information', content: new Model(type: BookDetails::class))]
    #[OA\Response(response: 404, description: 'Book not found')]
    #[Route(path: '/api/v1/books/{id}', methods: 'GET|OPTIONS')]
    public function booksById(int $id): Response
    {
        return $this->json($this->bookCategoryService->getBookById($id));
    }

    #[OA\Parameter(name: 'page', description: 'Page Number', in: 'query', schema: new OA\Schema(type: 'integer'))]
    #[OA\Response(response: 200, description: 'Return book reviews', content: new Model(type: ReviewPage::class))]
    #[OA\Response(response: 404, description: 'Book not found')]
    #[Route(path: '/api/v1/books/review/{id}', methods: 'GET|OPTIONS')]
    public function reviews(int $id, Request $request): Response
    {
        return $this->json($this->reviewService->getReviewPageByBookId(
            $id,
            $request->query->get(key: 'page', default: 1),
        ));
    }
}
