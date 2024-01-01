<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\DocBlock\Tag;

use TypeLang\Parser\Node\SerializableInterface;

interface TagInterface extends SerializableInterface, \Stringable
{
    /**
     * @return non-empty-string
     *
     * @psalm-immutable
     */
    public function getName(): string;

    /**
     * Returns description of the tag.
     *
     * @psalm-immutable
     */
    public function getDescription(): \Stringable|string|null;

    /**
     * Magic method {@link https://www.php.net/manual/en/language.oop5.magic.php#object.tostring}
     * allows a class to decide how it will react when it is treated like
     * a string.
     *
     * @psalm-immutable
     * @return string Returns string representation of the object that
     *         implements this interface (and/or {@see __toString()} magic
     *         method).
     */
    public function __toString(): string;
}
