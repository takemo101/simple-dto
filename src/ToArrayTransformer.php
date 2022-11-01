<?php

namespace Takemo101\SimpleDTO;

/**
 * to array transformer interface
 */
interface ToArrayTransformer
{
    /**
     * transform by array
     *
     * @param array<string,mixed> $keyValues
     * @return array<string,mixed>
     */
    public function transform(array $keyValues): array;
}
