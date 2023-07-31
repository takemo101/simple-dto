<?php

namespace Takemo101\SimpleDTO\Contracts;

/**
 * Casting implementation to convert array values ​​to object property values ​​and vice versa
 *
 * @template FromType
 * @template ToType
 */
interface ValueCastable
{
    /**
     * Convert from array values ​​to object property values
     *
     * @param FromType $value
     * @return ToType
     */
    public function castToObject($value);

    /**
     * Convert from object property value to array value
     *
     * @param ToType $value
     * @return FromType
     */
    public function castToArray($value);
}
