<?php

namespace Takemo101\SimpleDTO\Attributes;

use Attribute;

/**
 * You can set default values ​​for properties when converting from an array to an object
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
final class PropDefault
{
    /**
     * constructor
     *
     * @param mixed $value
     */
    public function __construct(
        public readonly mixed $value,
    ) {
        //
    }
}
