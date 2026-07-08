<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\TypeAliasTag;

use TypeLang\PhpDoc\DocBlock\Reference\TypeReference;
use TypeLang\PhpDoc\DocBlock\Tag\Tag;
use TypeLang\Type\TypeNode;

/**
 * A tag that declares a local type alias: a name bound to a type expression.
 */
final class TypeAliasTag extends Tag
{
    public TypeNode $type {
        get => $this->statement->type;
    }

    public function __construct(
        string $name,
        /**
         * The name the type is bound to.
         *
         * @var non-empty-string
         */
        public readonly string $alias,
        /**
         * The parsed type together with its original source text.
         */
        protected readonly TypeReference $statement,
    ) {
        parent::__construct($name);
    }

    #[\Override]
    public function __toString(): string
    {
        return \sprintf('@%s %s = %s', $this->name, $this->alias, $this->statement);
    }
}
