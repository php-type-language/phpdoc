<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\DocBlock\Tag;

use TypeLang\Parser\Node\Stmt\TypeStatement;

abstract class TemplateInheritanceTag extends Tag implements TypeProviderInterface
{
    /**
     * @param non-empty-string $name
     */
    public function __construct(
        string $name,
        public readonly TypeStatement $type,
        \Stringable|string|null $description = null,
    ) {
        parent::__construct($name, $description);
    }
}
