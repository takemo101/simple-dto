<?php

namespace Takemo101\SimpleDTO\Attributes;

use Attribute;
use InvalidArgumentException;

/**
 * rename array properties attribute class
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class Rename
{
    /**
     * @var string
     */
    private const RenamePattern = RegexPatternConfig::VariableNamePattern;

    /**
     * constructor
     *
     * @param string $name
     * @throws InvalidArgumentException
     */
    public function __construct(
        private readonly string $name,
    ) {
        if (strlen($name) == 0 || !preg_match(self::RenamePattern, $name)) {
            throw new InvalidArgumentException("constructor argument error: [{$name}] is a string that cannot be used in the name");
        }
    }

    /**
     * get name to change
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }
}
