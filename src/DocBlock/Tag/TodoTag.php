<?php

declare(strict_types=1);

namespace TypeLang\PhpDocParser\DocBlock\Tag;

final class TodoTag extends Tag
{
    public function __construct(\Stringable|string|null $description = null)
    {
        parent::__construct('todo', $description);
    }
}
