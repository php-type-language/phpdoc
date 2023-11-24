<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

/**
 * @link https://docs.phpdoc.org/3.0/guide/references/phpdoc/tags/ignore.html#ignore
 */
final class IgnoreTag extends Tag implements CreatableFromDescriptionInterface
{
    public function __construct(\Stringable|string|null $description = null)
    {
        parent::__construct('ignore', $description);
    }

    public static function createFromDescription(\Stringable|string|null $description = null): self
    {
        return new self($description);
    }
}
