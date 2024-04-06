<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag\Description;

/**
 * Any class that implements this interface is a description object
 * that can be represented as a raw string scalar value.
 */
interface DescriptionInterface extends \Stringable
{
    /**
     * Returns a plain string representation of this description.
     *
     * Magic method {@link https://www.php.net/manual/en/language.oop5.magic.php#object.tostring}
     * allows a class to decide how it will react when it is treated like
     * a string.
     *
     * @psalm-immutable Each call to the method must return the same value.
     */
    public function __toString(): string;
}
