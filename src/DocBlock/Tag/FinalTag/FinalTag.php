<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\FinalTag;

use TypeLang\PHPDoc\DocBlock\Tag\Tag;

/**
 * Used to denote that the associated _Symbol_ is final, are not allowed to
 * extend or override the _Symbol_ in a child element.
 *
 * In some situations the language construct final cannot be used by the
 * implementing library where the functionality of the library prevents
 * elements from being final. For example when proxy patterns are applied.
 * In these cases the "`@final`" tag can be used to indicate that the element
 * should be treated as final.
 *
 * The optional description is used to provide a more detailed explanation of
 * why the element is marked as final.
 *
 * IDE's and other tools can use this information to show an error when such
 * an element is extended or overridden.
 *
 * ```
 * "@final" [<description>]
 * ```
 */
final class FinalTag extends Tag
{
    public function __construct(
        string $name,
        \Stringable|string|null $description = null,
    ) {
        parent::__construct($name, $description);
    }
}
