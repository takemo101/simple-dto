<?php

namespace Takemo101\SimpleDTO\Attributes\Casts;

use Attribute;
use InvalidArgumentException;
use Takemo101\SimpleDTO\Contracts\ToObjectable;
use Takemo101\SimpleDTO\Contracts\ValueCastable;

/**
 * Attribute that casts an object array of a class that implements Objectable
 *
 * @implements ValueCastable<array<integer,array<string,mixed>>,ToObjectable[]>
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
final class ToObjectsCast implements ValueCastable
{
    /**
     * constructor
     *
     * @param class-string<ToObjectable> $objectableClass
     * @throws InvalidArgumentException
     */
    public function __construct(
        private readonly string $objectableClass,
    ) {
        if (!is_subclass_of($objectableClass, ToObjectable::class)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The specified class %s does not implement ToObjectable',
                    $this->objectableClass,
                ),
            );
        }
    }

    /**
     * Convert from array values ​​to object property values
     *
     * @param array<integer,array<string,mixed>> $value
     * @return ToObjectable[]
     */
    public function castToObject($value)
    {
        if (!is_array($value)) {
            return [];
        }

        /** @var ToObjectable[] */
        $result = [];

        foreach ($value as $key => $item) {
            $class = $this->objectableClass;
            $result[$key] = ($class)::__fromArray($item);
        }

        return $result;
    }

    /**
     * Convert from object property value to array value
     *
     * @param ToObjectable[] $value
     * @return array<integer,array<string,mixed>>
     */
    public function castToArray($value)
    {
        /** @var array<integer,array<string,mixed>> */
        $result = $value;

        return $result;
    }
}
