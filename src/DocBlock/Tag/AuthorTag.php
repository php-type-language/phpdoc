<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

/**
 * TODO Replace description to required "name" string scalar.
 * TODO Add email parsing support.
 *
 * @link https://docs.phpdoc.org/3.0/guide/references/phpdoc/tags/author.html#author
 */
final class AuthorTag extends Tag implements CreatableFromDescriptionInterface
{
    public function __construct(\Stringable|string|null $description = null)
    {
        parent::__construct('author', $description);
    }

    public static function createFromDescription(\Stringable|string|null $description = null): self
    {
        return new self($description);
    }
}
