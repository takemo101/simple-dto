<?php

namespace Takemo101\SimpleDTO\Traits;

use Takemo101\SimpleDTO\SimpleDTOFacade;

/**
 * make the functionality of a simple DTO available
 */
trait HasSimpleDTO
{
    /**
     * @var SimpleDTOFacade|null
     */
    private ?SimpleDTOFacade $___facadeCache = null;

    /**
     * get the output result of the getter method as the value of the property
     *
     * @param string $key
     * @return mixed
     */
    public function __get(string $key)
    {
        if (is_null($this->___facadeCache)) {
            $this->___facadeCache = new SimpleDTOFacade($this);
        }

        if ($output = $this->___facadeCache->findMethod($key)) {
            return $output->output();
        }

        return null;
    }

    /**
     * get the instance as an array.
     *
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        if (is_null($this->___facadeCache)) {
            $this->___facadeCache = new SimpleDTOFacade($this);
        }

        return $this->___facadeCache->toArray();
    }
}
