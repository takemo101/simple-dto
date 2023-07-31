<?php

namespace Takemo101\SimpleDTO\Contracts;

/**
 * Implementation that arrays from an object
 */
interface ToArrayable
{
    /**
     * convert from own instance to array
     *
     * @return array<string,mixed>
     */
    public function __toArray(): array;
}
