<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\CategoryTag;

use TypeLang\PHPDoc\DocBlock\Tag\Tag;

/**
 * Used to organize groups of packages together.
 *
 * The "`@category`" tag was meant in the original de-facto Standard to group
 * several _Elements_ by their "`@package`" tags into one category.
 * These categories could then be used to aid in the
 * generation of API documentation.
 *
 * This was necessary since the "`@package`" tag, as defined in the
 * original Standard, did not allow for more than one hierarchy
 * level. Since this has changed this tag SHOULD NOT be used.
 *
 * Please see the documentation for "`@package`" for details of its usage.
 *
 * ```
 * "@category" [<description>]
 * ```
 */
final class CategoryTag extends Tag
{
    public function __construct(
        string $name,
        \Stringable|string|null $description = null,
    ) {
        parent::__construct($name, $description);
    }
}
