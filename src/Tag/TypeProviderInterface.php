<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag;

use TypeLang\Parser\Node\Stmt\TypeStatement;

/**
 * Every class that implements a given interface is an implementation of a
 * tag that contains type information (that is, an AST object).
 *
 * Requires a `type-lang/parser` dependency for {@see TypeStatement} support.
 *
 * @psalm-suppress UndefinedClass : Expects optional `type-lang/parser` dependency.
 */
interface TypeProviderInterface
{
    /**
     * Returns an AST object of the type.
     */
    public function getType(): TypeStatement;
}
