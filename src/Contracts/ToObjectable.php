<?php

namespace Takemo101\SimpleDTO\Contracts;

/**
 * An implementation that converts an object from an array
 */
interface ToObjectable
{
    /**
     * instantiate itself from an array
     *
     * @param array<string,mixed> $data
     * @return static
     */
    public static function __fromArray(array $data): static;
}
