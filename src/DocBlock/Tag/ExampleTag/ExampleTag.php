<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\ExampleTag;

use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Reference\UriReference;
use TypeLang\PhpDoc\DocBlock\Tag\Tag;

/**
 * The "@example" tag points at an external source that illustrates the use of
 * the documented element, optionally narrowing it to a range of lines starting
 * at a line and spanning a number of lines.
 */
final class ExampleTag extends Tag
{
    public function __construct(
        string $name,
        /**
         * The location of the illustrating source.
         */
        public readonly UriReference $location,
        /**
         * @var int<0, max>|null
         */
        public readonly ?int $start = null,
        /**
         * @var int<0, max>|null
         */
        public readonly ?int $count = null,
        ?DescriptionInterface $description = null,
    ) {
        parent::__construct($name, $description);
    }

    #[\Override]
    public function __toString(): string
    {
        $result = \sprintf('@%s %s', $this->name, $this->location);

        if ($this->start !== null) {
            $result .= ' ' . $this->start;
        }

        if ($this->count !== null) {
            $result .= ' ' . $this->count;
        }

        if ($this->description !== null) {
            $result .= ' ' . $this->description;
        }

        return $result;
    }
}
