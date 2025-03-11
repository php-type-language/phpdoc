<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\SeeTag;

use TypeLang\PHPDoc\DocBlock\Tag\Shared\Reference\ElementReference;
use TypeLang\PHPDoc\DocBlock\Tag\Shared\Reference\ReferenceInterface;
use TypeLang\PHPDoc\DocBlock\Tag\Shared\Reference\UriReference;
use TypeLang\PHPDoc\DocBlock\Tag\Tag;

/**
 * The "`@see`" tag can be used to define a {@see ElementReference element} or
 * to an {@see UriReference external URI}.
 *
 * When defining a reference to other elements, you can refer to a specific
 * element by appending a double colon and providing the name of that element
 * (also called the 'Fully Qualified Name' or _FQN_).
 *
 * A URI MUST be complete and well-formed as specified in RFC 2396.
 *
 * The "`@see"` tag SHOULD have a description to provide additional information
 * regarding the relationship between the element and its target.
 *
 * The "`@see`" tag cannot refer to a namespace element.
 *
 * ```
 * "@see" [URI | FQN] [<description>]
 * ```
 *
 * @link https://www.ietf.org/rfc/rfc2396.txt RFC2396
 */
final class SeeTag extends Tag
{
    public function __construct(
        string $name,
        public readonly ReferenceInterface $ref,
        \Stringable|string|null $description = null,
    ) {
        parent::__construct($name, $description);
    }
}
