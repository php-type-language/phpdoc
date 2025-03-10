<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\MethodTag;

use TypeLang\Parser\Node\Stmt\Callable\CallableParameterNode;
use TypeLang\Parser\Node\Stmt\Callable\ParameterNode;
use TypeLang\Parser\Node\Stmt\TypeStatement;
use TypeLang\PHPDoc\DocBlock\Tag\OptionalTypeProviderInterface;
use TypeLang\PHPDoc\DocBlock\Tag\Tag;

/**
 * The "`@method`" tag is used in situations where a class contains the
 * `__call()` or `__callStatic()` magic method and defines some definite uses.
 *
 * An example of this is a child class whose parent has a `__call()` method
 * defined to have dynamic getters or setters for predefined properties. The
 * child knows which getters and setters need to be present, but relies on the
 * parent class to use the `__call()` method to provide this functionality. In
 * this situation, the child class would have a "`@method`" tag for each magic
 * setter or getter method.
 *
 * The "`@method`" tag allows the author to communicate the type of the
 * arguments and return value by including those types in the signature.
 *
 * When the intended method does not have a return value then the return type
 * MAY be omitted; in which case '`void`' is implied.
 *
 * If the intended method is static, the static keyword can be placed before
 * the return type to communicate that. In that case, a return type MUST be
 * provided, as static on its own would mean that the method returns an instance
 * of the child class which the method is called on.
 *
 * The "`@method`" tags MUST NOT be used in a PHPDoc unless it is associated
 * with a class or interface.
 *
 * ```
 * "@method" [static] <CallableType> [<description>]
 * "@method" [static] <ReturnType> <CallableType> [<description>]
 * "@method" [static] <CallableType>: <ReturnType> [<description>]
 * ```
 */
class MethodTag extends Tag implements OptionalTypeProviderInterface
{
    /**
     * @var list<ParameterNode|CallableParameterNode>
     */
    public readonly array $parameters;

    /**
     * @param non-empty-string $name
     * @param non-empty-string $method
     * @param iterable<mixed, ParameterNode|CallableParameterNode> $parameters
     */
    public function __construct(
        string $name,
        /**
         * @var non-empty-string
         */
        public readonly string $method,
        public readonly ?TypeStatement $type = null,
        iterable $parameters = [],
        public readonly bool $isStatic = false,
        \Stringable|string|null $description = null,
    ) {
        $this->parameters = match (true) {
            $parameters instanceof \Traversable => \iterator_to_array($parameters, false),
            \array_is_list($parameters) => $parameters,
            default => \array_values($parameters),
        };

        parent::__construct($name, $description);
    }
}
