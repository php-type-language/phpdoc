<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

/**
 * TODO Add version extraction support: {@link https://docs.phpdoc.org/3.0/guide/references/phpdoc/tags/version.html#version}
 */
final class VersionTag extends Tag
{
    public function __construct(\Stringable|string|null $description = null)
    {
        parent::__construct('version', $description);
    }
}
