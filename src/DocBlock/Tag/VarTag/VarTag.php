<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\VarTag;

use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Reference\TypeReference;
use TypeLang\PhpDoc\DocBlock\Tag\TypedTag;

/**
 * The "@var" tag documents the type of a property, constant or inline
 * variable, optionally naming the variable it applies to.
 */
final class VarTag extends TypedTag
{
    public function __construct(
        string $name,
        TypeReference $statement,
        /**
         * Name of the documented variable, without the leading "$", or
         * {@see null} when the tag does not name one.
         *
         * @var non-empty-string|null
         */
        public readonly ?string $variable = null,
        ?DescriptionInterface $description = null,
    ) {
        parent::__construct($name, $statement, $description);
    }

    #[\Override]
    public function __toString(): string
    {
        $result = \sprintf('@%s %s', $this->name, $this->statement);

        if ($this->variable !== null) {
            $result .= ' $' . $this->variable;
        }

        if ($this->description !== null) {
            $result .= ' ' . $this->description;
        }

        return $result;
    }
}
