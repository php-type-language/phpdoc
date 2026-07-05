<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\SourceTag;

use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Tag\Tag;

/**
 * The "@source" tag points at a range of lines of the documented element's
 * source, starting at a line and optionally spanning a number of lines.
 */
final class SourceTag extends Tag
{
    public function __construct(
        string $name,
        /**
         * @var int<0, max>
         */
        public readonly int $start,
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
        $result = \sprintf('@%s %d', $this->name, $this->start);

        if ($this->count !== null) {
            $result .= ' ' . $this->count;
        }

        if ($this->description !== null) {
            $result .= ' ' . $this->description;
        }

        return $result;
    }
}
