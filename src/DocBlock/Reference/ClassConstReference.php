<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Reference;

use TypeLang\Parser\Node\Identifier;
use TypeLang\Parser\Node\Name;

/**
 * Representation of the "Foo\Any::CONST" format.
 */
final class ClassConstReference implements ReferenceInterface
{
    public function __construct(
        public readonly Name $class,
        public readonly Identifier $const,
    ) {}

    public function __toString(): string
    {
        return \vsprintf('%s::%s', [
            $this->class->toString(),
            $this->const->toString(),
        ]);
    }
}
