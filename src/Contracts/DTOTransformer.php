<?php

namespace Takemo101\SimpleDTO\Contracts;

/**
 * dto transformer interface
 */
interface DTOTransformer
{
    /**
     * transform for array
     *
     * @param array<string,mixed> $data
     * @return array<string,mixed>
     */
    public function transformToArray(array $data): array;

    /**
     * transform for object
     *
     * @param array<string,mixed> $data
     * @return array<string,mixed>
     */
    public function transformToObject(array $data): array;
}
