<?php

namespace Takemo101\SimpleDTO\Transformers;

/**
 * simple dto transformer
 */
class SimpleTransformer extends AbstractTransformer
{
    /**
     * constructor
     *
     * @param string[] $arrayableMethods
     */
    public function __construct(
        private readonly array $arrayableMethods = [
            '__toArray',
            'toArray',
        ],
    ) {
        //
    }

    /**
     * transform to array value
     *
     * @param mixed $value
     * @return mixed
     */
    protected function transformToArrayValue(mixed $value): mixed
    {
        return $this->transformToArrayValueIfObject($value);
    }

    /**
     * transform for object
     *
     * @param mixed $value
     * @return mixed
     */
    private function transformToArrayValueIfObject(mixed $value): mixed
    {
        if (!is_object($value)) {
            return $value;
        }

        foreach ($this->arrayableMethods as $method) {
            if (method_exists($value, $method)) {
                /** @var mixed */
                $result = $value->{$method}();

                return $result;
            }
        }

        return $value;
    }
}
