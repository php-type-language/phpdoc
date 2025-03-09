<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag;

/**
 * Representation of any entry that MAY contain an optional variable name.
 */
interface OptionalVariableProviderInterface
{
    /**
     * The name of the variable (parameter, field, etc.) to which this
     * interface is attached or {@see null} in case of
     * entry does not contain a name.
     *
     * @var non-empty-string|null
     * @readonly
     */
    public ?string $variable { get; }
}
