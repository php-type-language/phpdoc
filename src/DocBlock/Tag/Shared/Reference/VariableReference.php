<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\Shared\Reference;

use TypeLang\Parser\Node\Literal\VariableLiteralNode;

/**
 * Related to local variable reference
 */
final class VariableReference extends ElementReference
{
    public function __construct(
        public readonly VariableLiteralNode $variable,
    ) {}
}
