<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag;

use TypeLang\Parser\Node\Stmt\TypeStatement;

/**
 * Requires a `type-lang/parser` dependency for {@see TypeStatement} support.
 *
 * @psalm-suppress UndefinedClass : Expects optional `type-lang/parser` dependency `type-lang/parser`.
 */
abstract class TypedTag extends Tag
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
