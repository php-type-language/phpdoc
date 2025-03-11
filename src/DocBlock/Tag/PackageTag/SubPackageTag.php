<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\PackageTag;

use TypeLang\Parser\Node\Name;
use TypeLang\PHPDoc\DocBlock\Tag\Tag;

/**
 * Used to categorize _Element(s)_ into logical subdivisions.
 *
 * The "`@subpackage`" tag can be used as a counterpart or supplement to
 * Namespaces. Namespaces provide a functional subdivision of _Element(s)_
 * where the "`@subpackage`" tag can provide a logical subdivision in
 * which way the elements can be grouped with a different hierarchy.
 *
 * If, across the board, both logical and functional subdivisions
 * are equal, it is NOT RECOMMENDED to use the "`@subpackage`"
 * tag to prevent maintenance overhead.
 *
 * The "`@subpackage`" tag MUST only be used in a select set of
 * DocBlocks, as is described in the documentation for the "`@package`"
 * tag. It MUST also accompany a "`@package`" tag and may occur
 * only once per DocBlock.
 *
 * This tag is considered superseded by the support for multiple levels
 * in the "`@package`" tag and is as such considered deprecated.
 *
 * ```
 * ""`@subpackage`"" <namespace> [<description>]
 * ```
 */
class SubPackageTag extends Tag
{
    public function __construct(
        string $name,
        public readonly Name $package,
        \Stringable|string|null $description = null,
    ) {
        parent::__construct($name, $description);
    }
}
