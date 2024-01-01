<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\Parser\DocBlock\Tag;

use TypeLang\Parser\Node\Stmt\TypeStatement;

interface CreatableFromNameTypeAndDescriptionInterface extends
    TagInterface,
    TypeProviderInterface,
    VariableNameProviderInterface
{
    /**
     * Creates a new tag instance from required "name" identifier, required
     * {@see TypeStatement} type statement and arbitrary optional
     * string-like description arguments.
     *
     * @param non-empty-string $name
     */
    public static function createFromNameTypeAndDescription(
        string $name,
        TypeStatement $type,
        \Stringable|string|null $description = null,
    ): self;
}
