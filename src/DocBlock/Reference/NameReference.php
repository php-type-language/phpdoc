<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Reference;

use TypeLang\Parser\Node\Name;

/**
 * Representation of the "CONST", "Foo\Class" or something like that.
 */
final class NameReference implements ReferenceInterface
{
    public function __construct(
        public readonly Name $name,
    ) {}

    public function __toString(): string
    {
        return $this->name->toString();
    }
}
