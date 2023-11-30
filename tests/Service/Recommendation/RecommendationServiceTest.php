<?php

namespace App\Tests\Service\Recommendation;

use App\Exception\RecommendationAccessDeniedException;
use App\Exception\RecommendationRequestException;
use App\Service\Recommendation\RecommendationService;
use App\Tests\AbstractTestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class RecommendationServiceTest extends AbstractTestCase
{
    private SerializerInterface $serializer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->serializer = $this->createMock(SerializerInterface::class);
    }


    public static function dataProvider(): array /** @phpstan-ignore-line */
    {
        return [
            [Response::HTTP_FORBIDDEN, RecommendationAccessDeniedException::class],
            [Response::HTTP_BAD_GATEWAY, RecommendationRequestException::class],
        ];
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetRecommendationByBookId(int $code, string $executionClass): void
    {
        $this->expectException($executionClass);

        $httpClient = new MockHttpClient(new MockResponse(body: '', info: ['http_code' => $code]), 'https://example.com');

        $service = new RecommendationService(recommendationClient: $httpClient, serializer: $this->serializer);

        $service->getRecommendationByBookId(1);
    }
}
