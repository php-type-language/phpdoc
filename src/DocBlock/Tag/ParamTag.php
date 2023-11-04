<?php

declare(strict_types=1);

namespace TypeLang\Reader\DocBlock\Tag;

use TypeLang\Parser\Node\Stmt\TypeStatement;
use TypeLang\Reader\DocBlock\Description;

final class ParamTag extends TypedTag
{
    public function __construct(TypeStatement $type, Description|string|null $description = null)
    {
        parent::__construct('param', $type, $description);
    }
}
