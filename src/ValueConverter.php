<?php

namespace Takemo101\SimpleDTO;

use Takemo101\SimpleDTO\Contracts\ToArrayable;
use Takemo101\SimpleDTO\Contracts\ValueCastable;
use Takemo101\SimpleDTO\Filters\ValueFilters;

final class ValueConverter
{
    /**
     * constructor
     *
     * @param ValueFilters $filters
     */
    public function __construct(
        private readonly ValueFilters $filters,
    ) {
        //
    }

    /**
     * Convert values ​​to be arrayed
     *
     * @param mixed $value
     * @param ValueCastable<mixed,mixed> ...$castables
     * @return mixed
     */
    public function convert(
        mixed $value,
        ValueCastable ...$castables,
    ): mixed {

        foreach ($castables as $castable) {
            $value = $castable->castToArray($value);
        }

        $value = $this->filters->filter($value);

        if ($value instanceof ToArrayable) {
            return $value->__toArray();
        }

        // If it is an array, convert it recursively
        if (is_array($value)) {

            /** @var mixed[] */
            $result = [];

            foreach ($value as $key => $_value) {
                $result[$key] = $this->convert($_value);
            }

            return $result;
        }

        return $value;
    }

    /**
     * create an empty instance
     *
     * @return self
     */
    public static function empty(): self
    {
        return new self(
            ValueFilters::empty(),
        );
    }
}
