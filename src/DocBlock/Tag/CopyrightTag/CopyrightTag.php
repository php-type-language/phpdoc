<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\CopyrightTag;

use TypeLang\PHPDoc\DocBlock\Tag\Tag;

/**
 * Used to document the copyright information of any _Element_.
 *
 * The "`@copyright`" tag defines who holds the copyright over the _Element_.
 * The copyright indicated with this tag applies to the _Element_ with which
 * it is associated and all child elements unless otherwise noted.
 *
 * The format of the description is governed by the coding standard of each
 * individual project. It is RECOMMENDED to mention the year or years which
 * are covered by this copyright and the organization involved.
 *
 * ```
 * "@copyright" [<description>]
 * ```
 */
final class CopyrightTag extends Tag
{
    public function __construct(
        string $name,
        \Stringable|string|null $description = null,
    ) {
        parent::__construct($name, $description);
    }
}
