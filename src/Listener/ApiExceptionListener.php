<?php

namespace App\Listener;

use App\Service\ExceptionHandler\ExceptionMapping;
use App\Service\ExceptionHandler\ExceptionMappingResolver;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class ApiExceptionListener
{
    public function __construct(
        private ExceptionMappingResolver $exceptionMappingResolver,
        private readonly LoggerInterface $logger,
        private readonly SerializerInterface $serializer
    ) {
    }

    public function __invoke(ExceptionEvent $exceptionEvent): void
    {
        $throwable = $exceptionEvent->getThrowable();
        $mapping = $this->exceptionMappingResolver->resolve(get_class($throwable));

        if (!$mapping) {
            $mapping = new ExceptionMapping(code: Response::HTTP_INTERNAL_SERVER_ERROR, hidden: true, loggable: false);
        }

        if ($mapping->getCode() >= Response::HTTP_INTERNAL_SERVER_ERROR || $mapping->isLoggable()) {
            $this->logger->error(
                $throwable->getMessage(),
                [
                    'trace' => $throwable->getTraceAsString(),
                    'previous' => null !== $throwable->getPrevious() ? $throwable->getPrevious()->getMessage() : '',
                ]
            );
        }

        $message = $mapping->isHidden() ? Response::$statusTexts[$mapping->getCode()] : $throwable->getMessage();

        $data = $this->serializer->serialize(data: new class($message) {
            public function __construct(private readonly string $message)
            {
            }

            public function getMessage(): string
            {
                return $this->message;
            }
        }, format: JsonEncoder::FORMAT);

        $response = new JsonResponse(data: $data, status: $mapping->getCode(), json: true);

        $exceptionEvent->setResponse($response);
    }
}
