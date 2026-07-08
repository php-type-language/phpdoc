<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\CheckTypeTag;

use TypeLang\PhpDoc\DocBlock\Reference\TypeReference;
use TypeLang\PhpDoc\DocBlock\Tag\Tag;
use TypeLang\Type\TypeNode;

/**
 * A tag that checks the inferred type of a variable against an expected type.
 */
abstract class CheckTypeTag extends Tag
{
    public TypeNode $type {
        get => $this->statement->type;
    }

    public function __construct(
        string $name,
        /**
         * The variable whose inferred type is checked.
         *
         * @var non-empty-string
         */
        public readonly string $variable,
        /**
         * The expected type together with its original source text.
         */
        protected readonly TypeReference $statement,
    ) {
        parent::__construct($name);
    }

    #[\Override]
    public function __toString(): string
    {
        return \sprintf('@%s $%s = %s', $this->name, $this->variable, $this->statement);
    }
}
