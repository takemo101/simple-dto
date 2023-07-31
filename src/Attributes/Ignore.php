<?php

namespace Takemo101\SimpleDTO\Attributes;

use Attribute;

/**
 * ignore array properties attribute class
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD)]
final class Ignore
{
    //
}
