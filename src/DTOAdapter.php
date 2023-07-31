<?php

namespace Takemo101\SimpleDTO;

use Takemo101\SimpleDTO\Contracts\ValueFilterable;

/**
 * dto adapter
 */
final class DTOAdapter
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
     */
    public function __construct(
        object $object,
    ) {
        $this->finder = GetterMethodFinder::fromObject($object);
        $this->adapter = ObjectToArrayAdapter::fromObject($object);
    }

    /**
     * find getter method output
     *
     * @param string $method
     * @return MethodOutput|null
     */
    public function findMethodOutput(string $method): ?MethodOutput
    {
        return $this->finder->find($method);
    }

    /**
     * object to property and method array
     *
     * @param ValueConverter $converter
     * @return array<string,mixed>
     */
    public function toArray(
        ValueConverter $converter,
    ): array {
        $propertyData = $this->adapter->toArray($converter);

        $methodData = $this->finder->toArray($converter);

        /** @var array<string,mixed> */
        $data = [
            ...$propertyData,
            ...$methodData,
        ];

        return $data;
    }
}
