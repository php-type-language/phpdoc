<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag;

use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;

/**
 * A tag whose body is a single name identifier, followed by an optional
 * description.
 */
abstract class IdentifierTag extends Tag
{
    public function __construct(
        string $name,
        /**
         * The single identifier carried by the tag.
         *
         * @var non-empty-string
         */
        public readonly string $identifier,
        ?DescriptionInterface $description = null,
    ) {
        parent::__construct($name, $description);
    }

    #[\Override]
    public function __toString(): string
    {
        $result = \sprintf('@%s %s', $this->name, $this->identifier);

        if ($this->description !== null) {
            $result .= ' ' . $this->description;
        }

        return $result;
    }
}
