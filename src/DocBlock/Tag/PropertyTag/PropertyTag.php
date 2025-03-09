<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\PropertyTag;

use TypeLang\Parser\Node\Stmt\TypeStatement;
use TypeLang\PHPDoc\DocBlock\Tag\OptionalTypeProviderInterface;
use TypeLang\PHPDoc\DocBlock\Tag\Tag;
use TypeLang\PHPDoc\DocBlock\Tag\VariableProviderInterface;

/**
 * The "`@property`" tag is used when a class or trait implements the `__get()`
 * and/or `__set()` 'magic' methods to resolve non-literal properties
 * at run-time.
 *
 * The "`@property-read`" and "`@property-write`" variants MAY be used to
 * indicate 'magic' properties that can only be read or written.
 *
 * For example, the "`@property-read`" tag could be used when a class contains
 * a `__get()` magic method which allows for specific names, while those names
 * are not covered in the `__set()` magic method.
 *
 * | Property supported via  | Tag to use          |
 * |-------------------------|---------------------|
 * | `__get()` and `__set()` | "`@property`"       |
 * | `__get()` only          | "`@property-read`"  |
 * | `__set()` only          | "`@property-write`" |
 *
 * The "`@property`", "`@property-read`" and "`@property-write`" tags can ONLY
 * be used in a PHPDoc that is associated with a class or trait.
 *
 * - Also @see PropertyRead for "`@property-read`" tag implementation.
 * - Also @see PropertyWrite for "`@property-write`" tag implementation.
 *
 * ```
 * * @property[<-read|-write>] [<Type>] $<name> [<description>]
 * ```
 */
class PropertyTag extends Tag implements
    OptionalTypeProviderInterface,
    VariableProviderInterface
{
    /**
     * @param non-empty-string $name
     * @param non-empty-string $variable
     */
    public function __construct(
        string $name,
        public readonly ?TypeStatement $type,
        public readonly string $variable,
        \Stringable|string|null $description = null,
    ) {
        parent::__construct($name, $description);
    }
}
