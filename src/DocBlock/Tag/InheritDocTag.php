<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

final class InheritDocTag extends Tag
{
    public function __construct(\Stringable|string|null $description = null)
    {
        parent::__construct('inheritDoc', $description);
    }
}
