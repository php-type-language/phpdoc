<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag;

use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;

/**
 * A tag whose body is a single variable, followed by an optional description.
 */
abstract class VariableTag extends Tag implements VariableTagInterface
{
    public function __construct(
        string $name,
        /**
         * @var non-empty-string
         */
        public readonly string $variable,
        ?DescriptionInterface $description = null,
    ) {
        parent::__construct($name, $description);
    }

    #[\Override]
    public function __toString(): string
    {
        $result = \sprintf('@%s $%s', $this->name, $this->variable);

        if ($this->description !== null) {
            $result .= ' ' . $this->description;
        }

        return $result;
    }
}
