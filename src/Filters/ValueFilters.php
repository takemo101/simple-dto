<?php

namespace Takemo101\SimpleDTO\Filters;

use Takemo101\SimpleDTO\Contracts\ValueFilterable;

/**
 * abstract dto transformer
 */
final class ValueFilters implements ValueFilterable
{
    /**
     * @var ValueFilterable[]
     */
    private readonly array $filters;

    /**
     * constructor
     *
     * @param ValueFilterable ...$filters
     */
    public function __construct(
        ValueFilterable ...$filters,
    ) {
        $this->filters = $filters;
    }

    /**
     * Filter values ​​to array
     *
     * @param mixed $value
     * @return mixed
     */
    public function filter(mixed $value): mixed
    {
        $result = $value;

        foreach ($this->filters as $filter) {
            $result = $filter->filter($result);
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
