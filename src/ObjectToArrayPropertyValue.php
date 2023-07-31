<?php

namespace Takemo101\SimpleDTO;

use ReflectionAttribute;
use ReflectionProperty;
use Takemo101\SimpleDTO\Attributes\Rename;
use Takemo101\SimpleDTO\Attributes\Ignore;
use Takemo101\SimpleDTO\Contracts\ValueCastable;

/**
 * Class that handles property values
 */
final class ObjectToArrayPropertyValue
{
    /**
     * constructor
     *
     * @param mixed $value
     * @param ReflectionProperty $property
     */
    public function __construct(
        private readonly mixed $value,
        private readonly ReflectionProperty $property,
    ) {
        //
    }

    /**
     * is ignores property
     *
     * @return boolean
     */
    public function isIgnoreProperty(): bool
    {
        // Ignore static properties
        if ($this->property->isStatic()) {
            return true;
        }

        return count($this->property->getAttributes(Ignore::class)) > 0;
    }

    /**
     * get property name
     *
     * @return string
     */
    public function getPropertyName(): string
    {
        $attributes = $this->property->getAttributes(Rename::class);
        foreach ($attributes as $attribute) {
            /**
             * @var Rename
             */
            $rename = $attribute->newInstance();

            return $rename->name();
        }

        return $this->property->getName();
    }

    /**
     * get property value
     *
     * @param ValueConverter $converter
     * @return mixed
     */
    public function getPropertyValue(
        ValueConverter $converter,
    ): mixed {
        return $converter->convert(
            $this->value,
            ...$this->getPropertyCastables(),
        );
    }

    /**
     * get castables
     *
     * @return ValueCastable<mixed,mixed>[]
     */
    private function getPropertyCastables(): array
    {
        $result = [];

        // Perform cast processing if the ValueCastable attribute is set
        $attributes = $this->property->getAttributes(
            ValueCastable::class,
            ReflectionAttribute::IS_INSTANCEOF,
        );

        foreach ($attributes as $attribute) {
            /** @var ValueCastable<mixed,mixed> */
            $castable = $attribute->newInstance();

            $result[] = $castable;
        }

        return $result;
    }
}
