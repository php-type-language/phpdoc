<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Reference;

interface ReferenceInterface extends \Stringable
{
    /**
     * Returns string representation of the reference.
     */
    public function __toString(): string;
}
