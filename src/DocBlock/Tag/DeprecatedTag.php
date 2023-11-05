<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

/**
 * TODO Add version support: {@link https://docs.phpdoc.org/3.0/guide/references/phpdoc/tags/deprecated.html#deprecated}
 */
final class DeprecatedTag extends Tag
{
    public function __construct(\Stringable|string|null $description = null)
    {
        parent::__construct('deprecated', $description);
    }
}
