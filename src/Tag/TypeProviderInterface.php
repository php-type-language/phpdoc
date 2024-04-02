<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag;

use TypeLang\Parser\Node\Stmt\TypeStatement;

/**
 * @psalm-suppress UndefinedClass : Expects optional `type-lang/parser` dependency.
 */
interface TypeProviderInterface extends OptionalTypeProviderInterface
{
    /**
     * Returns an AST object of the type.
     *
     * @psalm-immutable Each call to the method must return the same value.
     */
    public function getType(): TypeStatement;
}
