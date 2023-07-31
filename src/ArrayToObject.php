<?php

namespace Takemo101\SimpleDTO;

use InvalidArgumentException;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;
use Takemo101\SimpleDTO\Attributes\PropDefault;
use Takemo101\SimpleDTO\Contracts\ToObjectable;
use Takemo101\SimpleDTO\Contracts\ValueCastable;

final class ArrayToObject
{
    /**
     * From array data and class name to object
     *
     * @param class-string $class
     * @param array<string,mixed> $data
     * @return object
     * @throws InvalidArgumentException
     */
    public function toObject(string $class, array $data): object
    {
        $reflectionClass = new ReflectionClass($class);

        $constructor = $reflectionClass->getConstructor();

        if (!$constructor) {
            throw new InvalidArgumentException(
                "The class {$class} does not have a constructor."
            );
        }

        $constructor->setAccessible(true);

        $values = $this->fromArrayToParameterData(
            $data,
            // Assign values ​​to constructor arguments
            $constructor->getParameters(),
        );

        /** @var object */
        $object = $reflectionClass->newInstanceWithoutConstructor();

        // init object property
        $constructor->invoke(
            $object,
            ...$values,
        );

        return $object;
    }

    /**
     * Generate Argument Parameter Values ​​from Array Data
     *
     * @param array<string,mixed> $data
     * @param ReflectionParameter[] $parameters
     * @return array<string,mixed>
     * @throws InvalidArgumentException
     */
    public function fromArrayToParameterData(
        array $data,
        array $parameters,
    ): array {
        /** @var array<string,mixed> */
        $values = [];

        foreach ($parameters as $parameter) {
            $name = $parameter->getName();

            // Set default value if argument value does not exist in array data
            $values[$name] = array_key_exists($name, $data)
                ? $this->convertParameterValue(
                    $this->castParameterValue(
                        $data[$name],
                        $parameter,
                    ),
                    $parameter,
                )
                : $this->convertParameterDefault($parameter);
        }

        return $values;
    }

    /**
     * cast the value
     *
     * @param mixed $value
     * @param ReflectionParameter $parameter
     * @return mixed
     */
    private function castParameterValue(
        mixed $value,
        ReflectionParameter $parameter,
    ): mixed {
        $result = $value;

        // Perform cast processing if the ValueCastable attribute is set
        $attributes = $parameter->getAttributes(
            ValueCastable::class,
            ReflectionAttribute::IS_INSTANCEOF,
        );

        if (count($attributes)) {
            foreach ($attributes as $castable) {
                /** @var ValueCastable */
                $castable = $castable->newInstance();

                $result = $castable->castToObject($result);
            }
        }

        return $result;
    }

    /**
     * convert the value to the value corresponding to the argument parameter
     *
     * @param mixed $value
     * @param ReflectionParameter $parameter
     * @return mixed
     */
    private function convertParameterValue(
        mixed $value,
        ReflectionParameter $parameter,
    ): mixed {
        if (!is_array($value)) {
            return $value;
        }

        if ($type = $parameter->getType()) {
            if ($type instanceof ReflectionNamedType) {

                $name = $type->getName();

                if ($object = $this->createObjectOr(
                    $name,
                    $value,
                )) {
                    return $object;
                }
            }
        }

        return $value;
    }


    /**
     * Convert to default value corresponding to argument parameter
     *
     * @param ReflectionParameter $parameter
     * @return mixed
     * @throws InvalidArgumentException
     */
    private function convertParameterDefault(
        ReflectionParameter $parameter,
    ): mixed {

        // If the PropDefault attribute is set, use that value as the default value
        if ($attributes = $parameter->getAttributes(PropDefault::class)) {
            return $attributes[0]->newInstance()->value;
        }

        // If a default value is set, use that value as the default value
        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }

        if ($type = $parameter->getType()) {

            if ($type instanceof ReflectionNamedType) {

                $name = $type->getName();

                if ($object = $this->createObjectOr($name)) {
                    return $object;
                }
            }

            // default to null if the type is nullable
            if ($type->allowsNull()) {
                return null;
            }
        }

        throw new InvalidArgumentException(
            "Argument {$parameter->getName()} has no default value"
        );
    }

    /**
     * Generate an instance that implements ToObjectable from the class name
     *
     * @param class-string<ToObjectable> $class
     * @param array<string,mixed> $data
     * @return ToObjectable|null
     */
    private function createObjectOr(
        string $class,
        array $data = [],
    ): ?ToObjectable {

        /** @var ToObjectable|null */
        $object = is_subclass_of(
            $class,
            ToObjectable::class,
        )
            ? ($class)::__fromArray($data)
            : null;

        return $object;
    }

    /**
     * From array data and class name to object
     *
     * @param class-string $class
     * @param array<string,mixed> $data
     * @return object
     */
    public static function fromArrayToObject(
        string $class,
        array $data,
    ): object {
        return (new self())->toObject($class, $data);
    }
}
