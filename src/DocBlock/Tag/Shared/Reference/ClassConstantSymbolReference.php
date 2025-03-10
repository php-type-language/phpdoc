<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\Shared\Reference;

use TypeLang\Parser\Node\Name;

/**
 * Related to any internal class property reference
 */
final class ClassConstantSymbolReference extends ClassSymbolReference
{
    public function __construct(
        Name $class,
        /**
         * @var non-empty-string
         */
        public readonly string $constant,
    ) {
        parent::__construct($class);
    }
}
