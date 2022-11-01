<?php

namespace Takemo101\SimpleDTO;

/**
 * simple dto facade
 */
final class SimpleDTOFacade
{
    /**
     * @var ToArrayTransformer[]
     */
    private static array $transformers = [];

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

        /** @var array<string,mixed> */
        $keyValues = [
            ...$propertyValues,
            ...$methodValues,
        ];

        return $this->transform($keyValues);
    }

    /**
     * transform by array
     *
     * @param array<string,mixed> $keyValues
     * @return array<string,mixed>
     */
    private function transform(array $keyValues): mixed
    {
        $result = $keyValues;

        foreach (self::$transformers as $transformer) {
            $result = $transformer->transform($result);
        }

        return $result;
    }

    /**
     * setup transformer
     *
     * @param ToArrayTransformer ...$transformers
     * @return void
     */
    public static function setup(ToArrayTransformer ...$transformers): void
    {
        self::$transformers = count($transformers) ?
            $transformers :
            [new ToArraySimpleTransformer()];
    }
}
