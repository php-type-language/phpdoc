<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

/**
 * TODO Add start line support.
 * TODO Add number of lines support.
 *
 * @link https://docs.phpdoc.org/3.0/guide/references/phpdoc/tags/source.html#source
 */
final class SourceTag extends Tag implements CreatableFromDescriptionInterface
{
    public function __construct(\Stringable|string|null $description = null)
    {
        parent::__construct('source', $description);
    }

    public static function createFromDescription(\Stringable|string|null $description = null): self
    {
        return new self($description);
    }
}
