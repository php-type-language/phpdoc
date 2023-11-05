<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

use TypeLang\PhpDocParser\DocBlock\Description;

/**
 * TODO Add lines support: {@link https://docs.phpdoc.org/3.0/guide/references/phpdoc/tags/source.html#source}
 */
final class SourceTag extends Tag
{
    public function __construct(Description|string|null $description = null)
    {
        parent::__construct('source', $description);
    }
}
