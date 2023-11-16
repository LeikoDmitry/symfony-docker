<?php

namespace App\Tests\Listener;

use App\Listener\ApiExceptionListener;
use App\Model\ErrorResponse;
use App\Service\ExceptionHandler\ExceptionMapping;
use App\Service\ExceptionHandler\ExceptionMappingResolver;
use App\Tests\AbstractTestCase;
use PHPUnit\Framework\MockObject\Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class ApiExceptionListenerTest extends AbstractTestCase
{
    /**
     * @throws Exception
     */
    public function testNone500MappingWithHiddenMessage(): void
    {
        $mapping = new ExceptionMapping(code: Response::HTTP_NOT_FOUND, hidden: true, loggable: false);
        $message = Response::$statusTexts[$mapping->getCode()];
        $body = json_encode(['error' => $message]);

        $resolver = $this->createMock(ExceptionMappingResolver::class);
        $resolver->expects($this->once())
            ->method('resolve')
            ->with(arguments: \InvalidArgumentException::class)
            ->willReturn($mapping);

        $logger = $this->createMock(LoggerInterface::class);
        $serializer = $this->createMock(SerializerInterface::class);
        $serializer->expects($this->once())
            ->method('serialize')
            ->with(new ErrorResponse($message), JsonEncoder::FORMAT)
            ->willReturn($body);

        $event = $this->createEvent(new \InvalidArgumentException('Test'));

        $listener = new ApiExceptionListener($resolver, $logger, $serializer, false);
        $listener($event);

        $response = $event->getResponse();

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertJsonStringEqualsJsonString($body, $response->getContent());
    }

    private function createEvent(\InvalidArgumentException $argumentException): ExceptionEvent
    {
        return new ExceptionEvent(
            $this->createTestKernel(),
            new Request(),
            HttpKernelInterface::MAIN_REQUEST,
            $argumentException
        );
    }

    private function createTestKernel(): HttpKernelInterface
    {
        return new class() implements HttpKernelInterface {
            public function handle(Request $request, int $type = self::MAIN_REQUEST, bool $catch = true): Response
            {
                return new Response('test');
            }
        };
    }
}
