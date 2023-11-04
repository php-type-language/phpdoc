<?php

declare(strict_types=1);

namespace TypeLang\Reader\DocBlock\Tag;

use TypeLang\Reader\DocBlock\Description;

final class ImmutableTag extends Tag
{
    public function __construct(Description|string|null $description = null)
    {
        parent::__construct('immutable', $description);
    }
}
