<?php

namespace App\Controller;

use App\Service\BookCategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    public function __construct(
        private readonly BookCategoryService $bookCategoryService
    ) {
    }

    #[Route(path: '/')]
    public function index(): Response
    {
        return $this->json($this->bookCategoryService->findAll());
    }
}
