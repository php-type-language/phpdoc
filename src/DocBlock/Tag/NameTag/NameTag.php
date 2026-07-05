<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\NameTag;

use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Tag\Tag;

/**
 * The "@name" tag assigns an alias to a procedural page or global variable so
 * that it can be referred to by that alias in the generated documentation.
 */
final class NameTag extends Tag
{
    public function __construct(
        string $name,
        /**
         * The alias assigned to the described element.
         *
         * @var non-empty-string
         */
        public readonly string $alias,
        ?DescriptionInterface $description = null,
    ) {
        parent::__construct($name, $description);
    }

    #[\Override]
    public function __toString(): string
    {
        $result = \sprintf('@%s %s', $this->name, $this->alias);

        if ($this->description !== null) {
            $result .= ' ' . $this->description;
        }

        return $result;
    }
}
