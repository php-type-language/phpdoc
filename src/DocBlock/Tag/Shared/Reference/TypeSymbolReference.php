<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\Shared\Reference;

use TypeLang\Parser\Node\Stmt\TypeStatement;

/**
 * Related to internal type reference
 */
final class TypeSymbolReference extends SymbolReference
{
    public function __construct(
        public readonly TypeStatement $type,
    ) {}
}
