<?php

namespace Takemo101\SimpleDTO\Traits;

use Takemo101\SimpleDTO\SimpleDTOFacade;

/**
 * Has the ability to create objects from array data
 */
trait HasArrayToObject
{
    /**
     * create an instance from an array
     *
     * @param array<string,mixed> $data
     * @return static
     */
    public static function __fromArray(array $data): static
    {
        return SimpleDTOFacade::fromArrayToDTO(
            static::class,
            $data,
        );
    }
}
