<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

use TypeLang\PhpDocParser\DocBlock\Description;

/**
 * TODO Add version support: {@link https://docs.phpdoc.org/3.0/guide/references/phpdoc/tags/since.html#since}
 */
final class SinceTag extends Tag
{
    public function __construct(Description|string|null $description = null)
    {
        parent::__construct('since', $description);
    }
}
