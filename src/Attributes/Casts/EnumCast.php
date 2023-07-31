<?php

namespace Takemo101\SimpleDTO\Attributes\Casts;

use Attribute;
use BackedEnum;
use InvalidArgumentException;
use Takemo101\SimpleDTO\Contracts\ValueCastable;

/**
 * Attribute casting Enum
 *
 * @template TEnum of BackedEnum
 * @extends ValueCastable<string|integer|null,TEnum|null>
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
final class EnumCast implements ValueCastable
{
    /**
     * constructor
     *
     * @param class-string<TEnum> $enumClass
     * @throws InvalidArgumentException
     */
    public function __construct(
        private readonly string $enumClass,
    ) {
        if (!is_subclass_of($enumClass, BackedEnum::class)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The specified class %s is not an Enum class',
                    $this->enumClass,
                ),
            );
        }
    }

    /**
     * Convert from array values ​​to object property values
     *
     * @param string|integer|null $value
     * @return TEnum|null
     */
    public function castToObject($value)
    {
        return is_null($value)
            ? null
            : ($this->enumClass)::tryFrom($value);
    }

    /**
     * Convert from object property value to array value
     *
     * @param TEnum|null $value
     * @return string|integer|null
     */
    public function castToArray($value)
    {
        if (is_null($value)) {
            return null;
        }

        if (!($value instanceof $this->enumClass)) {
            return null;
        }

        return $value->value;
    }
}
