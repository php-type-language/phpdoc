<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Tag;

use TypeLang\PHPDoc\Tag\Description\OptionalDescriptionProviderInterface;

interface TagInterface extends OptionalDescriptionProviderInterface, \Stringable
{
    /**
     * Returns the non-empty tag name string without the '@' prefix.
     *
     * Each tag name MUST match the pattern: `@[a-zA-Z_\x80-\xff\\\][\w\x80-\xff\-:\\\]*`,
     * that is, can contain all the characters that can match the PHP FQN, as
     * well as the '-' character.
     *
     * @return non-empty-string
     */
    public function getName(): string;

    /**
     * Magic method {@link https://www.php.net/manual/en/language.oop5.magic.php#object.tostring}
     * allows a class to decide how it will react when it is treated like
     * a string.
     *
     * @return string returns string representation of the object that
     *         implements this interface (and/or {@see __toString()} magic
     *         method)
     */
    public function __toString(): string;
}
