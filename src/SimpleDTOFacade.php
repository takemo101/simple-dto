<?php

namespace Takemo101\SimpleDTO;

/**
 * simple dto facade
 */
final class SimpleDTOFacade
{
    /**
     * @var GetterMethodFinder
     */
    private readonly GetterMethodFinder $finder;

    /**
     * @var ObjectToArrayAdapter
     */
    private readonly ObjectToArrayAdapter $adapter;

    /**
     * constructor
     *
     * @param object $object
     * @param string[] $ignores
     */
    public function __construct(
        object $object,
        array $ignores = [],
    ) {
        $this->finder = new GetterMethodFinder($object);
        $this->adapter = new ObjectToArrayAdapter($object, $ignores);
    }

    /**
     * find getter method output
     *
     * @param string $method
     * @return MethodOutput|null
     */
    public function findMethod(string $method): ?MethodOutput
    {
        return $this->finder->find($method);
    }

    /**
     * object to property and method array
     *
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        $propertyValues = $this->adapter->toArray();

        $methodValues = $this->finder->toArray();

        return array_merge(
            $methodValues,
            $propertyValues,
        );
    }
}
