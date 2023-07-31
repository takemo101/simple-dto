<?php

namespace Takemo101\SimpleDTO;

use ReflectionMethod;
use Takemo101\SimpleDTO\Attributes\Getter;
use Takemo101\SimpleDTO\Attributes\Ignore;
use Takemo101\SimpleDTO\Contracts\ToArrayable;

/**
 * Class that handles method values
 */
final class ObjectToArrayMethodValue
{
    /**
     * @var MethodOutput
     */
    public readonly MethodOutput $output;

    /**
     * constructor
     *
     * @param object $object
     * @param ReflectionMethod $method
     */
    public function __construct(
        object $object,
        private readonly ReflectionMethod $method,
    ) {
        $this->output = MethodOutput::from($object, $method);
    }

    /**
     * Is it a method that captures values ​​into an array?
     *
     * @return boolean
     */
    public function isGetterMethod(): bool
    {
        return !empty($this->method->getAttributes(Getter::class));
    }

    /**
     * is ignores method
     *
     * @return boolean
     */
    public function isIgnoreMethod(): bool
    {
        // Ignore static properties
        if ($this->method->isStatic()) {
            return true;
        }

        return count($this->method->getAttributes(Ignore::class)) > 0;
    }

    /**
     * get method name
     *
     * @return string
     */
    public function getMethodName(): string
    {
        $attributes = $this->method->getAttributes(Getter::class);

        $name = $this->method->getName();

        foreach ($attributes as $attribute) {
            /**
             * @var Getter
             */
            $getter = $attribute->newInstance();

            return $getter->needRename()
                ? $getter->name()
                : $name;
        }

        return $name;
    }

    /**
     * get method value
     *
     * @param ValueConverter $converter
     * @return mixed
     */
    public function getMethodValue(
        ValueConverter $converter,
    ): mixed {
        return $converter->convert(
            $this->output->output(),
        );
    }
}
