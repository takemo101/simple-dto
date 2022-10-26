<?php

namespace Takemo101\SimpleDTO\Attributes;

use Attribute;
use InvalidArgumentException;
use RuntimeException;

/**
 * make getter methods properties attribute class
 */
#[Attribute(Attribute::TARGET_METHOD)]
final class Getter
{
    /**
     * @var string
     */
    private const RenamePattern = RegexPatternConfig::VariableNamePattern;

    /**
     * constructor
     *
     * @param ?string $name
     * @throws InvalidArgumentException
     */
    public function __construct(
        private readonly ?string $name = null,
    ) {
        if (
            !is_null($name) &&
            (strlen($name) == 0 || !preg_match(self::RenamePattern, $name))
        ) {
            throw new InvalidArgumentException("constructor argument error: [{$name}] is a string that cannot be used in the name");
        }
    }

    /**
     * whether property renaming is required
     *
     * @return boolean
     */
    public function needRename(): bool
    {
        return !is_null($this->name);
    }

    /**
     * get name to change
     *
     * @return string
     */
    public function name(): string
    {
        if (is_null($this->name)) {
            throw new RuntimeException("property error: name is empty and cannot be referenced");
        }

        return $this->name;
    }
}
