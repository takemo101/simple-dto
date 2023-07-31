<?php

namespace Takemo101\SimpleDTO;

use Takemo101\SimpleDTO\Attributes\Getter;

/**
 * find the method
 */
final class GetterMethodFinder
{
    /**
     * @var array<string,ObjectToArrayMethodValue>
     */
    private readonly array $methodValues;

    /**
     * constructor
     *
     * @param ObjectToArrayMethodValue ...$methodValues
     */
    public function __construct(
        ObjectToArrayMethodValue ...$methodValues,
    ) {
        $_methodValues = [];

        foreach ($methodValues as $methodValue) {
            $_methodValues[$methodValue->getMethodName()] = $methodValue;
        }

        $this->methodValues = $_methodValues;
    }

    /**
     * find getter method output
     *
     * @param string $name
     * @return MethodOutput|null
     */
    public function find(string $name): ?MethodOutput
    {
        if (!array_key_exists($name, $this->methodValues)) {
            return null;
        }

        $methodValue = $this->methodValues[$name];

        return $methodValue->output;
    }

    /**
     * method output to array
     *
     * @param ValueConverter $converter
     * @return array<string,mixed>
     */
    public function toArray(ValueConverter $converter): array
    {
        /** @var array<string,mixed> */
        $result = [];

        foreach ($this->methodValues as $name => $methodValue) {
            if (!$methodValue->isIgnoreMethod()) {
                $result[$name] = $methodValue->getMethodValue(
                    $converter,
                );
            }
        }

        return $result;
    }

    /**
     * Undocumented function
     *
     * @param object $object
     * @return self
     */
    public static function fromObject(object $object): self
    {
        $classObject = new ObjectToArrayClassObject($object);

        return new self(...$classObject->createGetterMethodValues());
    }
}
