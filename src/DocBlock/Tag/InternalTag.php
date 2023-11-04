<?php

declare(strict_types=1);

namespace TypeLang\Reader\DocBlock\Tag;

use TypeLang\Reader\DocBlock\Description;

final class InternalTag extends Tag
{
    public function __construct(Description|string|null $description = null)
    {
        parent::__construct('internal', $description);
    }
}
