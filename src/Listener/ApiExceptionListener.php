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
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

readonly class ApiExceptionListener
{
    public function __construct(
        private ExceptionMappingResolver $exceptionMappingResolver,
        private LoggerInterface $logger,
        private SerializerInterface $serializer
    ) {
    }

    public function __invoke(ExceptionEvent $exceptionEvent): void
    {
        $throwable = $exceptionEvent->getThrowable();

        if ($this->isSecurityException($throwable)) {
            return;
        }

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

        $message = $throwable->getMessage();
        $details = new ErrorDebugDetails($message);

        $data = $this->serializer->serialize(data: new ErrorResponse($message, $details), format: JsonEncoder::FORMAT);

        $exceptionEvent->setResponse(new JsonResponse(data: $data, status: $mapping->getCode(), json: true));
    }

    private function isSecurityException(Throwable $throwable): bool
    {
        return $throwable instanceof AuthenticationException;
    }
}
