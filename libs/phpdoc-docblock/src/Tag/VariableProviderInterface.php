<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag;

/**
 * Representation of any entry that contain variable name.
 */
interface VariableProviderInterface extends OptionalVariableProviderInterface
{
    /**
     * The name of the variable (parameter, field, etc.) to which
     * this interface is attached.
     *
     * @var non-empty-string
     * @readonly
     */
    public string $variable { get; }
}
