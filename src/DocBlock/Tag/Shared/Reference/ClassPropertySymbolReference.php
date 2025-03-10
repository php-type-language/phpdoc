<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\Shared\Reference;

use TypeLang\Parser\Node\Name;

/**
 * Related to any internal class property reference
 */
final class ClassPropertySymbolReference extends ClassSymbolReference
{
    public function __construct(
        Name $class,
        /**
         * @var non-empty-string
         */
        public readonly string $property,
    ) {
        parent::__construct($class);
    }
}
