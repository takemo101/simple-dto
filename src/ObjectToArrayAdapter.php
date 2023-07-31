<?php

namespace Takemo101\SimpleDTO;

/**
 * convert from object to property array
 */
final class ObjectToArrayAdapter
{
    /**
     * constructor
     *
     * @param ObjectToArrayClassObject $classObject
     */
    public function __construct(
        private readonly ObjectToArrayClassObject $classObject,
    ) {
        //
    }

    /**
     * object to property array
     *
     * @param ValueConverter $converter
     * @return array<string,mixed>
     */
    public function toArray(ValueConverter $converter): array
    {
        return $this->mapWithKey(
            $this->classObject->createPropertyValues(),
            function (ObjectToArrayPropertyValue $propertyValue) use (
                $converter,
            ) {
                $name = $propertyValue->getPropertyName();

                if ($propertyValue->isIgnoreProperty()) {
                    return null;
                }

                return [
                    $name => $propertyValue->getPropertyValue(
                        $converter,
                    )
                ];
            },
        );
    }

    /**
     * array map with key
     *
     * @param mixed[] $data
     * @param callable $callback
     * @return array<string,mixed>
     */
    private function mapWithKey(array $data, callable $callback): array
    {
        $result = [];

        foreach ($data as $key => $value) {
            if ($assoc = (array)call_user_func_array($callback, [$value, $key])) {
                foreach ($assoc as $mapKey => $mapValue) {
                    $result[$mapKey] = $mapValue;
                }
            }
        }

        return $result;
    }

    /**
     * from object to property array
     *
     * @param object $object
     * @return self
     */
    public static function fromObject(
        object $object,
    ): self {
        return new self(
            new ObjectToArrayClassObject($object),
        );
    }
}
