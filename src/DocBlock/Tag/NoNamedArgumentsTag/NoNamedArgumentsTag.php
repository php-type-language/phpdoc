<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\NoNamedArgumentsTag;

use TypeLang\PHPDoc\DocBlock\Tag\Tag;

/**
 * Indicates that argument names may be changed in the future, and an update
 * may break backwards compatibility with unction calls using named arguments.
 *
 * ```
 * "@no-named-arguments" [<description>]
 * ```
 */
final class NoNamedArgumentsTag extends Tag
{
    public function __construct(
        string $name,
        \Stringable|string|null $description = null,
    ) {
        parent::__construct($name, $description);
    }
}
