<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\ParamTag;

use TypeLang\Parser\Node\Stmt\TypeStatement;
use TypeLang\PHPDoc\DocBlock\Tag\OptionalTypeProviderInterface;
use TypeLang\PHPDoc\DocBlock\Tag\Tag;
use TypeLang\PHPDoc\DocBlock\Tag\VariableProviderInterface;

/**
 * With the "`@param`" tag it is possible to document the type and the intent of
 * a single argument of a function or method. When provided it MAY contain a
 * type to indicate what is expected. The name of the argument MUST be present
 * so that it is clear which argument this tag relates to.
 *
 * The description is OPTIONAL yet RECOMMENDED, for instance, in case of
 * complicated structures, such as associative arrays.
 *
 * The "`@param`" tag MAY have a multi-line description and does not need
 * explicit delimiting.
 *
 * At a minimum, it is RECOMMENDED to use this tag with each argument whose code
 * signature does not provide type information.
 *
 * This tag MUST NOT occur more than once per argument in a PHPDoc and is
 * limited to structural elements of type method or function.
 *
 * ```
 *
 * * @param [<Type>] $<variable> [<description>]
 * ```
 */
class ParamTag extends Tag implements
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
        public readonly bool $isVariadic = false,
        public readonly bool $isOutput = false,
        \Stringable|string|null $description = null,
    ) {
        parent::__construct($name, $description);
    }
}
