<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

use TypeLang\PhpDocParser\DocBlock\Description;

/**
 * TODO Add url extraction: {@link https://docs.phpdoc.org/3.0/guide/references/phpdoc/tags/link.html#link}
 */
final class LinkTag extends Tag
{
    public function __construct(Description|string|null $description = null)
    {
        parent::__construct('link', $description);
    }
}
