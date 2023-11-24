<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

/**
 * TODO Add semantic versioning prefix support.
 *
 * @link https://docs.phpdoc.org/3.0/guide/references/phpdoc/tags/deprecated.html#deprecated
 */
final class DeprecatedTag extends Tag implements CreatableFromDescriptionInterface
{
    public function __construct(\Stringable|string|null $description = null)
    {
        parent::__construct('deprecated', $description);
    }

    public static function createFromDescription(\Stringable|string|null $description = null): self
    {
        return new self($description);
    }
}
