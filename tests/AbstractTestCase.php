<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use ReflectionClass;

abstract class AbstractTestCase extends TestCase
{
    /**
     * @throws \ReflectionException
     */
    protected function setEntityId(object $entity, int $value, string $field = 'id'): void
    {
        $class = new ReflectionClass($entity);

        $property = $class->getProperty($field);
        $property->setAccessible(true);
        $property->setValue($entity, $value);
        $property->setAccessible(false);
    }
}
