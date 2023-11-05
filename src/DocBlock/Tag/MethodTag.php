<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

/**
 * TODO Add support of full signature decoding: {@link https://docs.phpdoc.org/3.0/guide/references/phpdoc/tags/method.html#method}
 */
final class MethodTag extends Tag
{
    public function __construct(\Stringable|string|null $description = null)
    {
        parent::__construct('method', $description);
    }
}
