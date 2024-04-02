<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag;

use TypeLang\Parser\Node\Stmt\TypeStatement;

/**
 * @psalm-suppress UndefinedClass : Expects optional `type-lang/parser` dependency.
 */
abstract class TypedTag extends Tag implements TypeProviderInterface
{
    /**
     * @param non-empty-string $name
     */
    public function __construct(
        string $name,
        protected readonly TypeStatement $type,
        \Stringable|string|null $description = null,
    ) {
        parent::__construct($name, $description);
    }

    public function getType(): TypeStatement
    {
        return $this->type;
    }
}
