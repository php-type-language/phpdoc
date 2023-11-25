<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Reference;

use TypeLang\Parser\Node\Literal\VariableLiteralNode;
use TypeLang\Parser\Node\Name;

/**
 * Representation of the "Foo\Any::$property" format.
 */
final class ClassPropertyReference implements ReferenceInterface
{
    public function __construct(
        public readonly Name $class,
        public readonly VariableLiteralNode $var,
    ) {}

    public function __toString(): string
    {
        return \vsprintf('%s::%s', [
            $this->class->toString(),
            $this->var->getRawValue(),
        ]);
    }
}
