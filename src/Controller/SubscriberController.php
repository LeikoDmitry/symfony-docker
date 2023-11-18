<?php

namespace App\Controller;

use App\Model\SubscriberRequest;
use App\Service\SubscriberService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\ErrorResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

class SubscriberController extends AbstractController
{
    public function __construct(private readonly SubscriberService $subscriberService)
    {
    }

    /**
     * @OA\Response(
     *    response=201,
     *    description="Subscibe email to newsletters mailing list"
     * )
     *
     * @OA\RequestBody(@Model(type=SubscriberRequest::class))
     *
     * @OA\Response(
     *     response=400,
     *     description="Retun 400 error code if email exist",
     *     @Model(type=ErrorResponse::class)
     *  )
     * @OA\Response(
     *      response=422,
     *      description="Validation errors",
     *      @Model(type=ErrorResponse::class)
     * )
     */
    #[Route(path: '/api/v1/subscribe', methods: 'POST')]
    public function subscribe(#[MapRequestPayload] SubscriberRequest $subscriberRequest): Response
    {
        $this->subscriberService->subscribe($subscriberRequest);

        return $this->json(new ErrorResponse(
            message: 'Subscriber has been created successfully!',
            details: 'Operation has been completed with no errors'
        ), 201);
    }
}
