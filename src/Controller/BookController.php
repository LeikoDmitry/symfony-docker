<?php

namespace App\Controller;

use App\Model\BookListResponse;
use App\Service\BookService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    public function __construct(private readonly BookService $bookCategoryService)
    {
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
}
