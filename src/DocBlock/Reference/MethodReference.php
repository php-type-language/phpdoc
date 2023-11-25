<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Reference;

use TypeLang\Parser\Node\Identifier;
use TypeLang\Parser\Node\Name;

/**
 * Representation of the "Foo\Any::foo()" format.
 */
final class MethodReference implements ReferenceInterface
{
    public function __construct(
        public readonly Name $class,
        public readonly Identifier $method,
    ) {}

    public function __toString(): string
    {
        return \vsprintf('%s::%s()', [
            $this->class->toString(),
            $this->method->toString(),
        ]);
    }
}
