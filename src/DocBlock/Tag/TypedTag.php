<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag;

use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Reference\TypeReference;
use TypeLang\Type\TypeNode;

/**
 * A tag whose body is a single type followed by an optional description.
 */
abstract class TypedTag extends Tag implements TypedTagInterface
{
    /**
     * The type declared by the tag.
     */
    public readonly TypeNode $type;

    public function __construct(
        string $name,
        /**
         * The parsed type together with its original source text.
         */
        protected readonly TypeReference $statement,
        ?DescriptionInterface $description = null,
    ) {
        $this->type = $statement->type;

        parent::__construct($name, $description);
    }

    #[\Override]
    public function __toString(): string
    {
        $result = \sprintf('@%s %s', $this->name, $this->statement);

        if ($this->description !== null) {
            $result .= ' ' . $this->description;
        }

        return $result;
    }
}
