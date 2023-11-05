<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

/**
 * TODO Split description to url and name: {@link https://docs.phpdoc.org/3.0/guide/references/phpdoc/tags/license.html#license}
 */
final class LicenseTag extends Tag
{
    public function __construct(\Stringable|string|null $description = null)
    {
        parent::__construct('license', $description);
    }
}
