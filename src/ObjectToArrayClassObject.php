<?php

namespace Takemo101\SimpleDTO;

use ReflectionClass;
use ReflectionProperty;

/**
 * class for handling class objects
 */
final class ObjectToArrayClassObject
{
    /**
     * @var ReflectionClass<object>
     */
    private readonly ReflectionClass $class;

    /**
     * constructor
     *
     * @param object $object
     */
    public function __construct(
        private readonly object $object,
    ) {
        $this->class = new ReflectionClass($this->object);
    }

    /**
     * Create an array of ObjectToArrayPropertyValue from object property data
     *
     * @return ObjectToArrayPropertyValue[]
     */
    public function createPropertyValues(): array
    {
        /** @var ObjectToArrayPropertyValue[] */
        $result = [];

        $properties = $this->class->getProperties(
            ReflectionProperty::IS_PUBLIC,
        );

        foreach ($properties as $property) {
            $result[] = new ObjectToArrayPropertyValue(
                $property->getValue($this->object),
                $property,
            );
        }

        return $result;
    }

    /**
     * Create an array of ObjectToArrayMethodValue from the object's method data
     *
     * @return ObjectToArrayMethodValue[]
     */
    public function createGetterMethodValues(): array
    {
        /** @var ObjectToArrayMethodValue[] */
        $result = [];

        $methods = $this->class->getMethods();

        foreach ($methods as $method) {

            $methodValue = new ObjectToArrayMethodValue(
                $this->object,
                $method,
            );

            if ($methodValue->isGetterMethod()) {
                $result[] = $methodValue;
            }
        }

        return $result;
    }
}
