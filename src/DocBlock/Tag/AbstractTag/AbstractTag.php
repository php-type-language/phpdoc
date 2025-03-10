<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\AbstractTag;

use TypeLang\PHPDoc\DocBlock\Tag\Tag;

/**
 * Declare a class-like or method as abstract, as well as for declaring
 * what methods must be redefined in a child class.
 *
 * ```
 * "@abstract" [<description>]
 * ```
 */
final class AbstractTag extends Tag
{
    public function __construct(
        string $name,
        \Stringable|string|null $description = null,
    ) {
        parent::__construct($name, $description);
    }
}
