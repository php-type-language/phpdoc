<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\TemplateTag;

use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Reference\TypeReference;
use TypeLang\PhpDoc\DocBlock\Tag\Tag;

/**
 * A generic type parameter declaration: a name with an optional bound and an
 * optional default type.
 */
abstract class TypeParameterTag extends Tag
{
    public function __construct(
        string $name,
        /**
         * @var non-empty-string
         */
        public readonly string $parameter,
        public readonly ?TypeReference $bound = null,
        public readonly ?TypeReference $default = null,
        ?DescriptionInterface $description = null,
    ) {
        parent::__construct($name, $description);
    }

    #[\Override]
    public function __toString(): string
    {
        $result = \sprintf('@%s %s', $this->name, $this->parameter);

        if ($this->bound !== null) {
            $result .= ' of ' . $this->bound;
        }

        if ($this->default !== null) {
            $result .= ' = ' . $this->default;
        }

        if ($this->description !== null) {
            $result .= ' ' . $this->description;
        }

        return $result;
    }
}
