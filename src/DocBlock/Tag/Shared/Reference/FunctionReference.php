<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\Shared\Reference;

use TypeLang\Parser\Node\Name;

/**
 * Related to internal function reference
 */
final class FunctionReference extends SymbolReference
{
    public function __construct(
        public readonly Name $function,
    ) {}
}
