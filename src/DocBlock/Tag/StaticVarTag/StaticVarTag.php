<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\StaticVarTag;

use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Reference\TypeReference;
use TypeLang\PhpDoc\DocBlock\Tag\TypedTag;

/**
 * The `@staticvar` tag documents the type of static variable declared within
 * a function or method, optionally naming the variable it applies to.
 */
final class StaticVarTag extends TypedTag
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
