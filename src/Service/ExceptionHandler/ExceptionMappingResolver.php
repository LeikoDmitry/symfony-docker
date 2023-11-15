<?php

namespace App\Service\ExceptionHandler;

class ExceptionMappingResolver
{
    /** @var ExceptionMapping[] */
    private array $mappings;

    /**
     * @param array<string, array{code: int, hidden?: bool, loggable?: bool}> $mappings
     */
    public function __construct(array $mappings)
    {
        foreach ($mappings as $classKey => $mapping) {
            if (empty($mapping['code'])) {
                throw new \InvalidArgumentException(sprintf('Code is mandatory for class %s', $classKey));
            }

            $this->addMapping(
                class: $classKey,
                code: $mapping['code'],
                hidden: $mapping['hidden'] ?? false,
                loggable: $mapping['loggable'] ?? false
            );
        }
    }

    public function resolve(string $throwableClass): ?ExceptionMapping
    {
        $foundMapping = null;

        foreach ($this->mappings as $classKey => $mapping) {
            if ($throwableClass === $classKey || is_subclass_of($throwableClass, $classKey)) {
                $foundMapping = $mapping;
                break;
            }
        }

        return $foundMapping;
    }

    private function addMapping(string $class, int $code, bool $hidden, bool $loggable): void
    {
        $this->mappings[$class] = new ExceptionMapping(code: $code, hidden: $hidden, loggable: $loggable);
    }
}
