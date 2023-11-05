<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

/**
 * TODO Add url extraction: {@link https://docs.phpdoc.org/3.0/guide/references/phpdoc/tags/link.html#link}
 */
final class LinkTag extends Tag
{
    public function __construct(\Stringable|string|null $description = null)
    {
        parent::__construct('link', $description);
    }
}
