<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock;

/**
 * Represents a single component (partial) of a PhpDoc
 */
interface ComponentInterface extends \Stringable
{
    /**
     * Returns the string representation of the component.
     */
    public function __toString(): string;
}
