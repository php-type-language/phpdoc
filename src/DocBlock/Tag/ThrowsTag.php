<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag;

use TypeLang\Parser\Node\Stmt\TypeStatement;

/**
 * The "`@throws`" tag MAY be used to indicate that structural elements throw a
 * specific type of error.
 *
 * The type provided with this tag MUST represent an object that implements the
 * {@see \Throwable} interface, such as an {@see \Error}, {@see \Exception} or
 * any subclass thereof.
 *
 * This tag is used to present in your documentation which error COULD occur and
 * under which circumstances. It is RECOMMENDED to provide a description that
 * describes the reason an exception is thrown.
 *
 * It is also RECOMMENDED that this tag occurs for every occurrence of an
 * exception, even if it has the same type. By documenting every occurrence a
 * detailed view is created and the consumer knows for which errors to check.
 *
 * ```
 * * @throws [<Type>] [<description>]
 * ```
 */
class ThrowsTag extends Tag implements TypeProviderInterface
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
