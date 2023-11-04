<?php

declare(strict_types=1);

namespace TypeLang\Reader\DocBlock\Tag;

use TypeLang\Parser\Node\Stmt\TypeStatement;
use TypeLang\Reader\DocBlock\Description;

abstract class TypedTag extends Tag
{
    public function __construct(
        string $name,
        protected readonly TypeStatement $type,
        Description|string|null $description = null
    ) {
        parent::__construct($name, $description);
    }

    public function getType(): TypeStatement
    {
        return $this->type;
    }
}
