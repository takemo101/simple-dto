<?php

namespace Takemo101\SimpleDTO\Traits;

/**
 * make the functionality of a simple DTO available
 */
trait HasSimpleDTO
{
    use HasMinimalDTO;

    /**
     * get the instance as an array.
     *
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        return $this->__toArray();
    }

    /**
     * create an instance from an array
     *
     * @param array<string,mixed> $data
     * @return static
     */
    public static function fromArray(array $data): static
    {
        return self::__fromArray($data);
    }
}
