<?php

namespace App\Controller;

use App\Model\ErrorResponse;
use App\Model\SubscriberRequest;
use App\Service\SubscriberService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class SubscriberController extends AbstractController
{
    public function __construct(private readonly SubscriberService $subscriberService)
    {
    }

    #[OA\Response(response: 200, description: 'Subscribe email to newsletters mailing list')]
    #[OA\RequestBody(content: new Model(type: SubscriberRequest::class))]
    #[OA\Response(response: 400, description: 'Return 400 error code if email exist', content: new Model(type: ErrorResponse::class))]
    #[OA\Response(response: 422, description: 'Validation errors', content: new Model(type: ErrorResponse::class))]
    #[Route(path: '/api/v1/subscribes', name: 'subscribes', methods: 'POST')]
    public function subscribe(#[MapRequestPayload] SubscriberRequest $subscriberRequest): Response
    {
        $this->subscriberService->subscribe($subscriberRequest);

        return $this->json(new ErrorResponse(
            message: 'Subscriber has been created successfully!',
            details: 'Operation has been completed with no errors'
        ), 201);
    }
}
