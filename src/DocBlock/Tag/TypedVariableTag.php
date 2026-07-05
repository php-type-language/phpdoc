<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag;

use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Reference\TypeReference;

/**
 * A tag whose body is a type applied to a variable, followed by an optional
 * description.
 */
abstract class TypedVariableTag extends TypedTag implements VariableTagInterface
{
    public function __construct(
        string $name,
        TypeReference $statement,
        /**
         * @var non-empty-string
         */
        public readonly string $variable,
        ?DescriptionInterface $description = null,
    ) {
        parent::__construct($name, $statement, $description);
    }

    #[\Override]
    public function __toString(): string
    {
        $result = \sprintf('@%s %s $%s', $this->name, $this->statement, $this->variable);

        if ($this->description !== null) {
            $result .= ' ' . $this->description;
        }

        return $result;
    }
}
