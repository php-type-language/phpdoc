<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

use TypeLang\PhpDocParser\DocBlock\Description;

final class CopyrightTag extends Tag
{
    public function __construct(Description|string|null $description = null)
    {
        parent::__construct('copyright', $description);
    }
}
