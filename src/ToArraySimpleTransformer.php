<?php

namespace Takemo101\SimpleDTO;

/**
 * to array simple transformer
 */
final class ToArraySimpleTransformer implements ToArrayTransformer
{
    /**
     * transform by array
     *
     * @param array<string,mixed> $keyValues
     * @return array<string,mixed>
     */
    public function transform(array $keyValues): array
    {
        /** @var array<string,mixed> */
        $result = [];

        foreach ($keyValues as $key => $value) {
            $result[$key] = $this->transformArray(
                $this->transformObject($value),
            );
        }

        return $result;
    }

    /**
     * transform for object
     *
     * @param mixed $value
     * @return mixed
     */
    private function transformObject(mixed $value): mixed
    {
        return is_object($value) && method_exists($value, 'toArray') ?
            $value->toArray() :
            $value;
    }

    /**
     * transform for array
     *
     * @param mixed $value
     * @return mixed
     */
    private function transformArray(mixed $value): mixed
    {
        return is_array($value) ?
            $this->transform($value) :
            $value;
    }
}
