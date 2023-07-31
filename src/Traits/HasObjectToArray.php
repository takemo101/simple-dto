<?php

namespace Takemo101\SimpleDTO\Traits;

use Takemo101\SimpleDTO\DTOAdapter;
use Takemo101\SimpleDTO\SimpleDTOFacade;
use Takemo101\SimpleDTO\ValueConverter;

/**
 * Has the ability to create array data from objects
 */
trait HasObjectToArray
{
    /**
     * @var DTOAdapter|null
     */
    private ?DTOAdapter $__adapter = null;

    /**
     * get the adapter instance
     *
     * @return DTOAdapter
     */
    private function __adapter(): DTOAdapter
    {
        if (is_null($this->__adapter)) {
            $this->__adapter = new DTOAdapter($this);
        }

        return $this->__adapter;
    }

    /**
     * get the output result of the getter method as the value of the property
     *
     * @param string $key
     * @return mixed
     */
    public function __get(string $key)
    {
        if ($output = $this->__adapter()->findMethodOutput($key)) {
            return $output->output();
        }

        return null;
    }

    /**
     * get the instance as an array.
     *
     * @return array<string,mixed>
     */
    public function __toArray(): array
    {
        return SimpleDTOFacade::fromDTOToArray(
            $this->__adapter(),
        );
    }
}
