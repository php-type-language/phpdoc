<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag;

use TypeLang\PHPDoc\DocBlock\Description\OptionalDescriptionProviderInterface;

/**
 * Representation of the phpdoc tag.
 */
interface TagInterface extends OptionalDescriptionProviderInterface, \Stringable
{
    /**
     * Gets non-empty tag name string without the '@' prefix.
     *
     * Each tag name MUST match the pattern:
     *  - `@[a-zA-Z_\x80-\xff\\\][\w\x80-\xff\-:\\\]*`
     *
     * That is, can contain all the characters that can match the PHP FQN
     * (Fully Qualified Name), as well as the '-' character.
     *
     * @var non-empty-string
     *
     * @readonly
     */
    public string $name { get; }

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
