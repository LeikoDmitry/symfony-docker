<?php

namespace App\Controller;

use App\Model\ErrorResponse;
use App\Model\IdResponse;
use App\Model\SignUpRequest;
use App\Service\SignUpService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    public function __construct(private readonly SignUpService $signUpService)
    {
    }

    #[OA\Response(response: 200, description: 'Sign up a user', content: new Model(type: IdResponse::class))]
    #[OA\RequestBody(content: new Model(type: SignUpRequest::class))]
    #[OA\Response(response: 409, description: 'User already exist', content: new Model(type: ErrorResponse::class))]
    #[OA\Response(response: 422, description: 'Validation errors', content: new Model(type: ErrorResponse::class))]
    #[Route(path: '/api/v1/auth/signup', name: 'signup', methods: 'POST')]
    public function signUp(#[MapRequestPayload] SignUpRequest $signUpRequest): Response
    {
        return $this->signUpService->signUp(signUpRequest: $signUpRequest);
    }
}
