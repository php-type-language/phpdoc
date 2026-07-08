<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\ImportTypeAliasTag;

use TypeLang\PhpDoc\DocBlock\Reference\TypeReference;
use TypeLang\PhpDoc\DocBlock\Tag\Tag;
use TypeLang\Type\TypeNode;

/**
 * A tag that imports a type alias declared elsewhere, optionally rebinding it
 * to a local name.
 */
final class ImportTypeAliasTag extends Tag
{
    public TypeNode $type {
        get => $this->statement->type;
    }

    public function __construct(
        string $name,
        /**
         * The name of the imported type alias.
         *
         * @var non-empty-string
         */
        public readonly string $alias,
        /**
         * The type the alias is imported from, together with its source text.
         */
        protected readonly TypeReference $statement,
        /**
         * The local name the alias is rebound to, when present.
         *
         * @var non-empty-string|null
         */
        public readonly ?string $as = null,
    ) {
        parent::__construct($name);
    }

    #[\Override]
    public function __toString(): string
    {
        $result = \sprintf('@%s %s from %s', $this->name, $this->alias, $this->statement);

        if ($this->as !== null) {
            $result .= ' as ' . $this->as;
        }

        return $result;
    }
}
