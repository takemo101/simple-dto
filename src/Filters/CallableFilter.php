<?php

namespace Takemo101\SimpleDTO\Filters;

use Closure;
use Takemo101\SimpleDTO\Contracts\ValueFilterable;

/**
 * abstract dto transformer
 */
final class CallableFilter implements ValueFilterable
{
    /**
     * constructor
     *
     * @param Closure(mixed):mixed $handler
     */
    public function __construct(
        private readonly Closure $handler,
    ) {
        //
    }

    /**
     * Filter values ​​to array
     *
     * @param mixed $value
     * @return mixed
     */
    public function filter(mixed $value): mixed
    {
        return $this->handler->__invoke($value);
    }

    /**
     * create from callable
     *
     * @return self
     */
    public static function fromCallable(callable $callable): self
    {
        return new self(
            Closure::fromCallable($callable),
        );
    }
}
