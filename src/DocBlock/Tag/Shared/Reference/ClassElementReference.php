<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag\Shared\Reference;

use TypeLang\Parser\Node\Name;

/**
 * Related to any internal class reference
 */
abstract class ClassElementReference extends ElementReference
{
    public function __construct(
        public readonly Name $class,
    ) {}
}
