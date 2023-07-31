<?php

namespace Takemo101\SimpleDTO;

use Takemo101\SimpleDTO\Contracts\DTOTransformer;
use Takemo101\SimpleDTO\Contracts\ValueFilterable;
use Takemo101\SimpleDTO\Filters\ValueFilters;
use Takemo101\SimpleDTO\Transformers\DTOTransformers;

/**
 * Control arraying and DTO reconstruction
 */
final class SimpleDTO
{
    /**
     * constructor
     *
     * @param DTOTransformers $transformers
     * @param ValueConverter $converter
     */
    public function __construct(
        private readonly DTOTransformers $transformers,
        private readonly ValueConverter $converter,
    ) {
        //
    }

    /**
     * From DTO to array data
     *
     * @param object $dto
     * @return array<string,mixed>
     */
    public function fromDTOToArray(
        object $dto,
    ): array {
        $adapter = $dto instanceof DTOAdapter
            ? $dto
            : new DTOAdapter($dto);

        return $this->transformers->transformToArray(
            $adapter->toArray($this->converter),
        );
    }

    /**
     * From array data and class name to DTO
     *
     * @param class-string $class
     * @param array<string,mixed> $data
     * @return object
     */
    public function fromArrayToDTO(
        string $class,
        array $data,
    ): object {
        return ArrayToObject::fromArrayToObject(
            $class,
            $this->transformers->transformToObject($data),
        );
    }

    /**
     * set transformer
     *
     * @param DTOTransformer ...$transformers
     * @return self
     */
    public function setTransformers(
        DTOTransformer ...$transformers,
    ): self {
        return new self(
            new DTOTransformers(...$transformers),
            $this->converter,
        );
    }

    /**
     * set value filters
     *
     * @param ValueFilterable ...$filters
     * @return self
     */
    public function setFilters(
        ValueFilterable ...$filters,
    ): self {
        return new self(
            $this->transformers,
            new ValueConverter(
                new ValueFilters(...$filters),
            ),
        );
    }
}
