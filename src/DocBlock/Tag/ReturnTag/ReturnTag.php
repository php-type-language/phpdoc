<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\ReturnTag;

use TypeLang\Parser\Node\Stmt\TypeStatement;
use TypeLang\PHPDoc\DocBlock\Tag\Tag;
use TypeLang\PHPDoc\DocBlock\Tag\TypeProviderInterface;

/**
 * With the "`@return`" tag it is possible to document the return type of a
 * function or method. When provided it MUST contain a type to indicate what is
 * returned; the description on the other hand is OPTIONAL yet RECOMMENDED, for
 * instance, in case of complicated return structures, such as associative arrays.
 *
 * The "`@return`" tag MAY have a multi-line description and does not need
 * explicit delimiting.
 *
 * It is RECOMMENDED to use this tag with every function and method. Exceptions
 * to this recommendation, as defined by the coding standard of any individual
 * project, MAY be:
 *
 *  - Constructors, the "`@return`" tag MAY be omitted here, in which
 *    case "`@return`" self is implied.
 *  - Functions and methods without a `return` value, the "`@return`" tag MAY be
 *    omitted here, in which case "`@return`" void is implied.
 *
 * This tag MUST NOT occur more than once in a PHPDoc and is limited to
 * structural elements of type method or function.
 *
 * ```
 * "@return" [<Type>] [<description>]
 * ```
 */
class ReturnTag extends Tag implements TypeProviderInterface
{
    /**
     * @param non-empty-string $name
     */
    public function __construct(
        string $name,
        public readonly TypeStatement $type,
        \Stringable|string|null $description = null,
    ) {
        parent::__construct($name, $description);
    }
}
