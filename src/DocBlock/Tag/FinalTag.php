<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

final class FinalTag extends Tag
{
    public function __construct(\Stringable|string|null $description = null)
    {
        parent::__construct('final', $description);
    }
}
