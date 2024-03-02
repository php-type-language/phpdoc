<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag;

use TypeLang\PHPDoc\Tag\Definition\DefinitionInterface;
use TypeLang\PHPDoc\Tag\Definition\VarDefinition;

/**
 * @template-extends Tag<VarDefinition>
 */
final class VarTag extends Tag
{
    public function __construct(
        VarDefinition $definition,
        string $name,
        \Stringable|string|null $description = null
    ) {
        parent::__construct($definition, $name, $description);
    }
}
