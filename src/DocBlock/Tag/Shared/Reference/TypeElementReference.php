<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\Shared\Reference;

use TypeLang\Parser\Node\Stmt\TypeStatement;

/**
 * Related to internal type reference
 */
final class TypeElementReference extends ElementReference
{
    public function __construct(
        public readonly TypeStatement $type,
    ) {}
}
