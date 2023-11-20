<?php

namespace App\Listener;

use App\Model\ErrorDebugDetails;
use App\Model\ErrorResponse;
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
        private readonly ExceptionMappingResolver $exceptionMappingResolver,
        private readonly LoggerInterface $logger,
        private readonly SerializerInterface $serializer,
        private readonly bool $isDebug,
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
        $details = $this->isDebug && !$mapping->isLoggable() ? new ErrorDebugDetails($throwable->getTraceAsString()) : null;

        $data = $this->serializer->serialize(data: new ErrorResponse($message, $details), format: JsonEncoder::FORMAT);

        $exceptionEvent->setResponse(new JsonResponse(data: $data, status: $mapping->getCode(), json: true));
    }
}
