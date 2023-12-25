<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Reference;

use TypeLang\Parser\Node\SerializableInterface;

interface ReferenceInterface extends
    SerializableInterface,
    \Stringable
{
    /**
     * Returns string representation of the reference.
     */
    public function __toString(): string;
}
