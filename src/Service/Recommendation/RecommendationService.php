<?php

namespace App\Service\Recommendation;

use App\Exception\RecommendationAccessDeniedException;
use App\Exception\RecommendationRequestException;
use App\Model\Recommendation\RecommendationResponse;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

class RecommendationService
{
    public function __construct(
        private HttpClientInterface $recommendationClient,
        private SerializerInterface $serializer
    ) {
    }

    /**
     * @throws RecommendationAccessDeniedException
     * @throws RecommendationRequestException
     */
    public function getRecommendationByBookId(int $id): RecommendationResponse
    {
        try {
            $response = $this->recommendationClient->request(method: 'GET', url: '/api/v1/book/'.$id.'/recommendations');

            return $this->serializer->deserialize(
                data: $response->getContent(),
                type: RecommendationResponse::class,
                format: JsonEncoder::FORMAT
            );
        } catch (Throwable $exception) {
            $statusCode = $exception->getCode();

            if ($exception instanceof ClientException && Response::HTTP_FORBIDDEN === $statusCode) {
                throw new RecommendationAccessDeniedException();
            }

            throw new RecommendationRequestException($exception->getMessage(), $exception);
        }
    }
}
