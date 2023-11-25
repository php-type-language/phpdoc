<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Reference;

use TypeLang\Parser\Node\Name;

/**
 * Representation of the "foo()" format.
 */
final class FunctionReference implements ReferenceInterface
{
    public function __construct(
        public readonly Name $function,
    ) {}

    public function __toString(): string
    {
        return $this->function->toString() . '()';
    }
}
