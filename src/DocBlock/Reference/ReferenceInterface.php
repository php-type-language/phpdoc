<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\DocBlock\Reference;

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
