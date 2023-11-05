<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

use TypeLang\PhpDocParser\DocBlock\Description;

/**
 * TODO Add location support: {@link https://docs.phpdoc.org/3.0/guide/references/phpdoc/tags/example.html#example}
 */
final class ExampleTag extends Tag
{
    public function __construct(Description|string|null $description = null)
    {
        parent::__construct('example', $description);
    }
}
