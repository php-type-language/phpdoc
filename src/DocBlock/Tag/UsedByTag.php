<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

/**
 * TODO Add fqsen extraction: {@link https://docs.phpdoc.org/3.0/guide/references/phpdoc/tags/uses.html#uses-used-by}
 */
final class UsedByTag extends Tag
{
    public function __construct(\Stringable|string|null $description = null)
    {
        parent::__construct('used-by', $description);
    }
}
