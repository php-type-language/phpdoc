<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag;

use TypeLang\Parser\Node\Stmt\TypeStatement;

interface TypeProviderInterface extends OptionalTypeProviderInterface
{
    /**
     * Returns an AST object of the type.
     */
    public function getType(): TypeStatement;
}
