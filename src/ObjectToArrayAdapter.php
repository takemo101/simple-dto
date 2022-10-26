<?php

namespace Takemo101\SimpleDTO;

use ReflectionClass;
use ReflectionProperty;
use Takemo101\SimpleDTO\Attributes\Rename;
use Takemo101\SimpleDTO\Attributes\Ignore;

/**
 * convert from object to property array
 */
final class ObjectToArrayAdapter
{
    /**
     * constructor
     *
     * @param object $object
     * @param string[] $ignores
     */
    public function __construct(
        private readonly object $object,
        private readonly array $ignores = [],
    ) {
        //
    }

    /**
     * object to property array
     *
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        $class = new ReflectionClass($this->object);

        /**
         * @var ReflectionProperty[]
         */
        $reflections = array_filter(
            $class->getProperties(
                ReflectionProperty::IS_PUBLIC,
            ),
            fn (ReflectionProperty $reflection) => !$this->isIgnorePropertyByReflection($reflection),
        );

        return $this->arrayMapWithKey(
            $reflections,
            function (ReflectionProperty $reflection) {
                $name = $this->getPropertyNameByReflection($reflection);

                return in_array($name, $this->ignores) ?
                    null :
                    [$name => $reflection->getValue($this->object)];
            },
        );
    }

    /**
     * is ignores property by reflection
     *
     * @param ReflectionProperty $reflection
     * @return boolean
     */
    private function isIgnorePropertyByReflection(ReflectionProperty $reflection): bool
    {
        return count($reflection->getAttributes(Ignore::class)) > 0;
    }

    /**
     * get property name by reflection
     *
     * @param ReflectionProperty $reflection
     * @return string
     */
    private function getPropertyNameByReflection(ReflectionProperty $reflection): string
    {
        $attributes = $reflection->getAttributes(Rename::class);
        foreach ($attributes as $attribute) {
            /**
             * @var Rename
             */
            $rename = $attribute->newInstance();

            return $rename->name();
        }

        return $reflection->getName();
    }

    /**
     * array map with key
     *
     * @param mixed[] $data
     * @param callable $callback
     * @return array<string,mixed>
     */
    private function arrayMapWithKey(array $data, callable $callback): array
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
     * @param string[] $ignores
     * @return array<string,mixed>
     */
    public static function fromObjectToArray(
        object $object,
        array $ignores = [],
    ): array {
        return (new self($object, $ignores))->toArray();
    }
}
