<?php

namespace Takemo101\SimpleDTO\Contracts;

/**
 * An implementation that filters values
 */
interface ValueFilterable
{
    /**
     * Filter values ​​to array
     *
     * @param mixed $value
     * @return mixed
     */
    public function filter(mixed $value): mixed;
}
