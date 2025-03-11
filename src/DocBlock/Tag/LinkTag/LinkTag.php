<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\LinkTag;

use TypeLang\PHPDoc\DocBlock\Tag\Shared\Reference\UriReference;
use TypeLang\PHPDoc\DocBlock\Tag\Tag;

/**
 * The "`@link`" tag can be used to define a relation, or link, between
 * the element, or part of the long description when used inline, to a URI.
 *
 * The URI MUST be complete and well-formed as specified in RFC2396.
 *
 * The "`@link`" tag MAY have a description appended to indicate the type of
 * relation defined by this occurrence.
 *
 * ```
 * "@link" [URI] [<description>]
 * ```
 *
 * @link https://www.ietf.org/rfc/rfc2396.txt RFC2396
 */
final class LinkTag extends Tag
{
    public function __construct(
        string $name,
        public readonly UriReference $uri,
        \Stringable|string|null $description = null,
    ) {
        parent::__construct($name, $description);
    }
}
