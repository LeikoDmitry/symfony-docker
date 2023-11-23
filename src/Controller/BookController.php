<?php

namespace App\Controller;

use App\Model\BookDetails;
use App\Model\BookListResponse;
use App\Model\ReviewPage;
use App\Repository\ReviewRepository;
use App\Service\BookService;
use App\Service\ReviewService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use phpDocumentor\Reflection\Types\Self_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    private const LIMIT_REVIEW = 1;

    public function __construct(
        private readonly BookService $bookCategoryService,
        private readonly ReviewService $reviewService
    ) {
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Return book by category id",
     *
     *     @Model(type=BookListResponse::class)
     * )
     *
     * @OA\Response(
     *      response=404,
     *      description="Book category not found"
     *  )
     */
    #[Route(path: '/api/v1/books/category/{id}', methods: 'GET|OPTIONS')]
    public function booksByCategory(int $id): Response
    {
        return $this->json($this->bookCategoryService->findBooksByCategory($id));
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Return book details information",
     *
     *     @Model(type=BookDetails::class)
     * )
     *
     * @OA\Response(
     *      response=404,
     *      description="Book not found"
     *  )
     */
    #[Route(path: '/api/v1/books/{id}', methods: 'GET|OPTIONS')]
    public function booksById(int $id): Response
    {
        return $this->json($this->bookCategoryService->getBookById($id));
    }

    /**
     * @OA\Parameter(name="page", in="query", description="Page number", @OA\Schema(type="integer"))
     *
     * @OA\Response(
     *     response=200,
     *     description="Return book reviews",
     *
     *     @Model(type=ReviewPage::class)
     * )
     *
     * @OA\Response(
     *      response=404,
     *      description="Book not found"
     *  )
     */
    #[Route(path: '/api/v1/books/review/{id}', methods: 'GET|OPTIONS')]
    public function reviews(int $id, Request $request): Response
    {
        return $this->json($this->reviewService->getReviewPageByBookId(
            $id,
            $request->query->get(key: 'page', default: 1),
        ));
    }
}
