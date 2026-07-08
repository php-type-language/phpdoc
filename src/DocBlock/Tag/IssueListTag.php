<?php

declare(strict_types=1);

namespace TypeLang\PhpDoc\DocBlock\Tag;

use TypeLang\PhpDoc\DocBlock\Description\DescriptionInterface;

/**
 * A tag whose body is a comma-separated list of issue identifiers, followed by
 * an optional description.
 */
abstract class IssueListTag extends Tag
{
    public function __construct(
        string $name,
        /**
         * The identifiers of the diagnostics being listed.
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
