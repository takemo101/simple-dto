<?php

namespace Takemo101\SimpleDTO\Transformers;

use Takemo101\SimpleDTO\Contracts\DTOTransformer;

/**
 * abstract dto transformer
 */
abstract class AbstractTransformer implements DTOTransformer
{
    /**
     * transform for array
     *
     * @param array<string,mixed> $data
     * @return array<string,mixed>
     */
    public function transformToArray(array $data): array
    {
        /** @var array<string,mixed> */
        $result = [];

        foreach ($data as $key => $value) {
            $result[$this->transformToArrayKey($key)] = $this->transformToArrayValue($value);
        }

        return $result;
    }

    /**
     * transform for object
     *
     * @param array<string,mixed> $data
     * @return array<string,mixed>
     */
    public function transformToObject(array $data): array
    {
        /** @var array<string,mixed> */
        $result = [];

        foreach ($data as $key => $value) {
            $result[$this->transformToObjectKey($key)] = $this->transformToObjectValue($value);
        }

        return $result;
    }

    /**
     * transform to array key
     *
     * @param string $key
     * @return string
     */
    protected function transformToArrayKey(string $key): string
    {
        return $key;
    }

    /**
     * transform to array value
     *
     * @param mixed $value
     * @return mixed
     */
    protected function transformToArrayValue(mixed $value): mixed
    {
        return $value;
    }

    /**
     * transform to object key
     *
     * @param string $key
     * @return string
     */
    protected function transformToObjectKey(string $key): string
    {
        return $key;
    }

    /**
     * transform to object value
     *
     * @param mixed $value
     * @return mixed
     */
    protected function transformToObjectValue(mixed $value): mixed
    {
        return $value;
    }
}
