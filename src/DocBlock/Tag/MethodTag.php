<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

use TypeLang\PhpDocParser\DocBlock\Description;

/**
 * TODO Add support of full signature decoding: {@link https://docs.phpdoc.org/3.0/guide/references/phpdoc/tags/method.html#method}
 */
final class MethodTag extends Tag
{
    public function __construct(Description|string|null $description = null)
    {
        parent::__construct('method', $description);
    }
}
