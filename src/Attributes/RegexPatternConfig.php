<?php

namespace Takemo101\SimpleDTO\Attributes;

/**
 * regex pattern config class
 */
final class RegexPatternConfig
{
    /**
     * @var string
     */
    public const VariableNamePattern = '/^[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]+$/';
}
