<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\AccessTag;

use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Tag\Tag;

/**
 * The "@access" tag documents the visibility of an element.
 */
final class AccessTag extends Tag
{
    public function __construct(
        string $name,
        public readonly Visibility $access,
        ?DescriptionInterface $description = null,
    ) {
        parent::__construct($name, $description);
    }

    #[\Override]
    public function __toString(): string
    {
        $result = \sprintf('@%s %s', $this->name, $this->access->value);

        if ($this->description !== null) {
            $result .= ' ' . $this->description;
        }

        return $result;
    }
}
