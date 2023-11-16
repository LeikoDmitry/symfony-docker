<?php

namespace App\Tests\Service\ExceptionHandler;

use App\Service\ExceptionHandler\ExceptionMappingResolver;
use App\Tests\AbstractTestCase;
use Symfony\Component\HttpFoundation\Response;

class ExceptionMappingResolverTest extends AbstractTestCase
{
    public function testResolveThrowsExceptionOnEmptyCode(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new ExceptionMappingResolver(mappings: ['testClass' => ['hidden' => false]]); /* @phpstan-ignore-line */
    }

    public function testResolveToNullWhenNotFound(): void
    {
        $resolver = new ExceptionMappingResolver(mappings: []);

        $this->assertNull($resolver->resolve(throwableClass: \RuntimeException::class));
    }

    public function testResolveClassItself(): void
    {
        $resolver = new ExceptionMappingResolver(mappings: [\RuntimeException::class => ['code' => Response::HTTP_GATEWAY_TIMEOUT]]);
        $mapping = $resolver->resolve(\RuntimeException::class);

        $this->assertEquals(Response::HTTP_GATEWAY_TIMEOUT, $mapping->getCode());
        $this->assertFalse($mapping->isHidden());
        $this->assertFalse($mapping->isLoggable());
    }

    public function testResolveSubClass(): void
    {
        $resolver = new ExceptionMappingResolver(mappings: [\RuntimeException::class => ['code' => Response::HTTP_GATEWAY_TIMEOUT]]);
        $mapping = $resolver->resolve(\OutOfBoundsException::class);

        $this->assertEquals(Response::HTTP_GATEWAY_TIMEOUT, $mapping->getCode());
    }
}
