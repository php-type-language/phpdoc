<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag;

interface DescriptionInterface extends TagsProviderInterface, \Stringable
{
    /**
     * Returns the body template.
     *
     * @psalm-immutable Each call to the method must return the same value.
     */
    public function getTemplate(): string;

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