<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag;

use TypeLang\Parser\Node\Stmt\TypeStatement;

/**
 * Every class that implements a given interface is an implementation of a
 * tag that contains type information (that is, an AST object).
 *
 * Requires a `type-lang/parser` dependency for {@see TypeStatement} support.
 */
interface OptionalTypeProviderInterface
{
    /**
     * Returns an AST object of the type or {@see null} in case the
     * type is not specified.
     *
     * @psalm-immutable Each call to the method must return the same value.
     */
    public function getType(): ?TypeStatement;
}
