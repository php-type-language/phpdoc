<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\DocBlock\Tag;

use TypeLang\Parser\Node\Stmt\TypeStatement;

interface CreatableFromTagAndDescriptionInterface extends TagInterface, TypeProviderInterface
{
    /**
     * Creates a new tag instance from a required {@see TypeStatement} type
     * statement and arbitrary optional string-like description argument.
     */
    public static function createFromTagAndDescription(
        TypeStatement $type,
        \Stringable|string|null $description = null,
    ): self;
}
