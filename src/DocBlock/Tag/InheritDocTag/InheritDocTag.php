<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\InheritDocTag;

use TypeLang\PHPDoc\DocBlock\Tag\Tag;

/**
 * Used to directly inherit the long description from the parent
 * class in child classes.
 *
 * ```
 * "@inheritDoc" [<description>]
 * ```
 */
final class InheritDocTag extends Tag
{
    public function __construct(
        string $name,
        \Stringable|string|null $description = null,
    ) {
        parent::__construct($name, $description);
    }
}
