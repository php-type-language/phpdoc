<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\ApiTag;

use TypeLang\PHPDoc\DocBlock\Tag\Tag;

/**
 * Used to highlight _Symbol_ as being part of the primary
 * public API of a package.
 *
 * The "`@api`" tag may be applied to public _Symbol_ to highlight them
 * in generated documentation, pointing the consumer to the primary public
 * API components of a library or framework.
 *
 * When the "`@api`" tag is used, other _Symbol_ with a public
 * visibility serve to support the internal structure and
 * are not recommended to be used by the consumer.
 *
 * The exact meaning of _Symbol_ tagged with "`@api`" MAY differ per project.
 * It is however RECOMMENDED that all "`@api`" tagged _Symbol_ SHOULD
 * NOT change after publication unless the new version
 * is tagged as breaking Backwards Compatibility.
 *
 * See also the `"@internal"` tag, which can be used to hide internal
 * _Symbol_ from generated documentation.
 *
 * ```
 * "@api" [<description>]
 * ```
 */
final class ApiTag extends Tag
{
    public function __construct(
        string $name,
        \Stringable|string|null $description = null,
    ) {
        parent::__construct($name, $description);
    }
}
