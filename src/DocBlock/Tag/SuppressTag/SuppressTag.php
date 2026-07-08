<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag\SuppressTag;

use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;
use TypeLang\PhpDoc\DocBlock\Tag\Tag;

/**
 * The `@suppress` tag silences the listed diagnostics that would otherwise be
 * reported for an element.
 */
final class SuppressTag extends Tag
{
    public function __construct(
        string $name,
        /**
         * The identifiers of the diagnostics being silenced.
         *
         * @var non-empty-list<non-empty-string>
         */
        public readonly array $issues,
        ?DescriptionInterface $description = null,
    ) {
        parent::__construct($name, $description);
    }

    #[\Override]
    public function __toString(): string
    {
        $result = \sprintf('@%s %s', $this->name, \implode(', ', $this->issues));

        if ($this->description !== null) {
            $result .= ' ' . $this->description;
        }

        return $result;
    }
}
