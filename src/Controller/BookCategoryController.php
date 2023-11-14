<?php

namespace App\Controller;

use App\Model\BookCategoryListResponse;
use App\Service\BookCategoryService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookCategoryController extends AbstractController
{
    public function __construct(
        private readonly BookCategoryService $bookCategoryService
    ) {
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Return book categories",
     *
     *     @Model(type=BookCategoryListResponse::class)
     * )
     */
    #[Route(path: '/api/v1/categories', methods: 'GET|OPTIONS')]
    public function index(): Response
    {
        return $this->json($this->bookCategoryService->findAll());
    }
}
