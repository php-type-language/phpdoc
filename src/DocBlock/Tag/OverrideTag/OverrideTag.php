<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\OverrideTag;

use TypeLang\PHPDoc\DocBlock\Tag\Tag;

/**
 * Used is mention to see if the method is actually overriding a definition
 * or implementing an abstract method (Or a phpdoc "`@method`")
 * in an ancestor class/trait/interface.
 *
 * ```
 * "@override" [<description>]
 * ```
 */
final class OverrideTag extends Tag
{
    public function __construct(
        string $name,
        \Stringable|string|null $description = null,
    ) {
        parent::__construct($name, $description);
    }
}
