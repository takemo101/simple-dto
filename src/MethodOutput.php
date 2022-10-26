<?php

namespace Takemo101\SimpleDTO;

use ReflectionMethod;
use Closure;

/**
 * get method output from reflection
 */
final class MethodOutput
{
    /**
     * constructor
     *
     * @param Closure $handler
     */
    private function __construct(
        private readonly Closure $handler,
    ) {
        //
    }

    /**
     * get method output
     *
     * @param mixed ...$args
     * @return mixed
     */
    public function output(mixed ...$args): mixed
    {
        return $this->handler->call($this, ...$args);
    }

    /**
     * from method reflection
     *
     * @param object $object
     * @param ReflectionMethod $reflection
     * @return self
     */
    public static function from(
        object $object,
        ReflectionMethod $reflection,
    ): self {
        return new self(
            fn (mixed ...$args) => $reflection->invoke($object, ...$args),
        );
    }
}
