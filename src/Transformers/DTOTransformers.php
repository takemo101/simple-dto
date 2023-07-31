<?php

namespace Takemo101\SimpleDTO\Transformers;

use Takemo101\SimpleDTO\Contracts\DTOTransformer;

final class DTOTransformers implements DTOTransformer
{
    /**
     * @var DTOTransformer[]
     */
    private readonly array $transformers;

    /**
     * constructor
     *
     * @param DTOTransformer ...$transformers
     */
    public function __construct(
        DTOTransformer ...$transformers,
    ) {
        $this->transformers = $transformers;
    }

    /**
     * transform for array
     *
     * @param array<string,mixed> $data
     * @return array<string,mixed>
     */
    public function transformToArray(array $data): array
    {
        return $this->reduce(
            fn (
                array $data,
                DTOTransformer $transformer,
            ) => $transformer->transformToArray($data),
            $data,
        );
    }

    /**
     * transform for object
     *
     * @param array<string,mixed> $data
     * @return array<string,mixed>
     */
    public function transformToObject(array $data): array
    {
        return $this->reduce(
            fn (
                array $data,
                DTOTransformer $transformer,
            ) => $transformer->transformToObject($data),
            $data,
        );
    }

    /**
     * transformer reduce
     *
     * @param callable(array<string,mixed>,DTOTransformer):array<string,mixed> $callback
     * @param array<string,mixed> $initial
     * @return array<string,mixed>
     */
    private function reduce(
        callable $callback,
        array $initial,
    ): array {
        $result = $initial;

        foreach ($this->transformers as $transformer) {
            $result = $callback($result, $transformer);
        }

        return $result;
    }

    /**
     * create an empty instance
     *
     * @return self
     */
    public static function empty(): self
    {
        return new self();
    }
}
