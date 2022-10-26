<?php

namespace Takemo101\SimpleDTO;

use ReflectionClass;
use ReflectionMethod;
use Takemo101\SimpleDTO\Attributes\Getter;

/**
 * find the getter method
 */
final class GetterMethodFinder
{
    /**
     * @var array<string,ReflectionMethod>|null
     */
    private ?array $methods = null;

    /**
     * constructor
     *
     * @param object $object
     */
    public function __construct(
        private readonly object $object,
    ) {
        //
    }

    /**
     * find getter method output
     *
     * @param string $method
     * @return MethodOutput|null
     */
    public function find(string $method): ?MethodOutput
    {
        $methods = $this->getObjectReflectionMethods();

        return array_key_exists($method, $methods) ?
            MethodOutput::from($this->object, $methods[$method]) :
            null;
    }

    /**
     * method output to array
     *
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        /** @var array<string,mixed> */
        $result = [];

        foreach ($this->getObjectReflectionMethods() as $name => $method) {
            $result[$name] = MethodOutput::from($this->object, $method)->output();
        }

        return $result;
    }

    /**
     * get object reflection methods
     *
     * @return array<string,ReflectionMethod>
     */
    private function getObjectReflectionMethods(): array
    {
        if (is_null($this->methods)) {
            /** @var array<string,ReflectionMethod> */
            $methods = [];

            $reflections = (new ReflectionClass($this->object))->getMethods();

            foreach ($reflections as $reflection) {
                if ($name = $this->getMethodNameByReflection($reflection)) {
                    $methods[$name] = $reflection;
                }
            }

            $this->methods = $methods;
        }

        return $this->methods;
    }

    /**
     * get getter method name
     *
     * @param ReflectionMethod $reflection
     * @return string|null
     */
    private function getMethodNameByReflection(ReflectionMethod $reflection): ?string
    {
        $attributes = $reflection->getAttributes(Getter::class);
        foreach ($attributes as $attribute) {
            /**
             * @var Getter
             */
            $name = $attribute->newInstance();
            return $name->needRename() ? $name->name() : $reflection->getName();
        }

        return null;
    }
}
