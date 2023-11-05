<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

final class InternalTag extends Tag
{
    public function __construct(\Stringable|string|null $description = null)
    {
        parent::__construct('internal', $description);
    }
}
